<?php

namespace NewMonk\lib\error\sla;

class BreachManager
{
    private $statsToCheck;
    private $slaConfigManager;
    private $loggingConfigManager;
    private $elasticSearchDao;
    private $notifier;
    private $activityLogger;

    public function __construct(
        $slaConfigManager,
        $loggingConfigManager,
        $elasticSearchDao,
        $notifier,
        $activityLogger
    ) {
        $this->statsToCheck = [
            'errorCount' => 'Error Count'
        ];
        $this->slaConfigManager = $slaConfigManager;
        $this->loggingConfigManager = $loggingConfigManager;
        $this->elasticSearchDao = $elasticSearchDao;
        $this->notifier = $notifier;
        $this->activityLogger = $activityLogger;
    }

    public function notify($until) {
        $appAndDomainNames = $this->loggingConfigManager->getAppAndDomainNames('elogger');
        $breaches = [];
        foreach ($appAndDomainNames as $appId=>$appDetails) {
            list($hasSlaBeenBreached, $currentBreaches) = $this->checkBreach($appDetails, $until);
            if ($hasSlaBeenBreached) {
                $breaches[$appId] = $currentBreaches;
            }
        }
        $this->sendNotifications($breaches);

        $appIdsWithBreaches = array_keys($breaches);
        return $appIdsWithBreaches;
    }

    private function checkBreach($appDetails, $until) {
        $slaConfig = $this->slaConfigManager->getConfig($appDetails['appId']);
        $slaGlobalConfig = $this->slaConfigManager->getGlobalConfig();

        $breaches = ['breaches' => []];
        if (!empty($slaConfig)) {
            $sla = $this->fixMissingSlaConfig($slaConfig['sla'], $slaGlobalConfig);
            $from = $until - intval($sla['breachInterval'])*(
                substr($sla['breachInterval'], -1) == 'h'
                    ? 3600
                    : 60
            );
            $errorStats = $this->getErrorStats($appDetails, $from, $until);
            $currentBreaches = $this->checkBreachForErrorStats($sla, $errorStats, $from, $until);
            if (!empty($currentBreaches)) {
                $hasSlaBeenBreached = true;
                $breaches['breaches'] = $currentBreaches;
                $breaches['domainName'] = $appDetails['domainName'];
                $breaches['appName'] = $appDetails['appName'];
                $breaches['contact'] = $slaConfig['contact'];
            }
            $breaches = $this->filterBreachesBelowAllowedBreachCount($breaches);
        }
        $hasSlaBeenBreached = !empty($breaches['breaches']);

        return [
            $hasSlaBeenBreached,
            $breaches
        ];
    }

    private function fixMissingSlaConfig($sla, $slaGlobalConfig) {
        $fixedConfig = $sla;
        if (!isset($sla['breachInterval'])) {
            $fixedConfig['breachInterval'] = $slaGlobalConfig['sla']['breachInterval'];
        }
        if (!isset($sla['allowedBreachCount'])) {
            $fixedConfig['allowedBreachCount'] = $slaGlobalConfig['sla']['allowedBreachCount'];
        }
        return $fixedConfig;
    }

    private function getErrorStats($appDetails, $from, $until) {
        $errorCount = $this->elasticSearchDao->getCount($appDetails['appId'], $from, $until);
        return [
            [
                'errorCount' => $errorCount,
            ]
        ];
    }

    private function checkBreachForErrorStats($sla, $errorStats, $from, $until) {
        $breaches = [];
        foreach ($errorStats as $errorStat) {
            $breaches = array_merge($breaches, $this->checkBreachForErrorStat($sla, $errorStat, $from, $until));
        }
        return $breaches;
    }

    private function checkBreachForErrorStat($sla, $errorStat, $from, $until) {
        $breaches = [];
        foreach ($this->statsToCheck as $statShortName=>$statLongName) {
            if ($errorStat[$statShortName] > $sla[$statShortName]) {
                $breaches[] = [
                    'stat' => $statLongName,
                    'allowed' => $sla[$statShortName],
                    'observed' => $errorStat[$statShortName],
                    'between' => date('Hi', $from).'-'.date('Hi', $until).' hrs'
                ];
            }
        }
        return $breaches;
    }

    private function filterBreachesBelowAllowedBreachCount($breaches) {
        foreach ($breaches['breaches'] as $tag=>$tagWiseBreaches) {
            if (count($tagWiseBreaches) <= $sla['allowedBreachCount']) {
                unset($breaches['breaches'][$tag]);
            }
        }
        return $breaches;
    }

    private function sendNotifications($breaches) {
        $slaGlobalConfig = $this->slaConfigManager->getGlobalConfig();
        $contact = $slaGlobalConfig['contact'];

        foreach ($breaches as $appId=>$appWiseBreaches) {
            $messageParts['slack'] = [
                '*SLA breached in '
                    .$appWiseBreaches['domainName']
                    .' '.($appWiseBreaches['appName'] ? $appWiseBreaches['appName'] : $appId)
                    ."*\n"
            ];
            $messageParts['email'] = [
                '<html><body><b>SLA breached in '
                    .$appWiseBreaches['domainName']
                    .' '.($appWiseBreaches['appName'] ? $appWiseBreaches['appName'] : $appId)
                    .'</b><br />'
            ];
            $subject = 'SLA breached in '
                    .$appWiseBreaches['domainName']
                    .' '.($appWiseBreaches['appName'] ? $appWiseBreaches['appName'] : $appId);
            foreach ($appWiseBreaches['breaches'] as $breach) {
                $messageParts['slack'][] = '>'.$breach['stat'].': *'
                    .$breach['observed'].'* _(allowed: '.$breach['allowed'].')_ @ '.$breach['between']."\n";
                $messageParts['email'][] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$breach['stat'].': <b>'
                    .$breach['observed'].'</b> <i>(allowed: '.$breach['allowed'].')</i> @ '.$breach['between'].'<br />';
            }
            $messageParts['slack'][] = '________________________________';
            $messageParts['email'][] = '<hr /></body></html>';
            $message['slack'] = implode('', $messageParts['slack']);
            $message['email'] = implode('', $messageParts['email']);


            $to = implode(',', $appWiseBreaches['contact']['email']['to']);
            $headers = "MIME-Version: 1.0\r\n"
                ."Content-type: text/html; charset=iso-8859-1\r\n"
                .'From: newmonk@naukri.com';

            mail($to, $subject, $message['email'], $headers);
            $this->notifier->sendMessage(
                'NewMonk',
                $appWiseBreaches['contact']['slack']['webhookUrl'],
                $appWiseBreaches['contact']['slack']['channel'],
                $message['slack'],
                [
                    'icon_emoji' => ':chart_with_upwards_trend:'
                ]
            );
            $this->activityLogger->log($message);
        }
    }
}

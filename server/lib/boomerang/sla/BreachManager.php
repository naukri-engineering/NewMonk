<?php

namespace NewMonk\lib\boomerang\sla;

class BreachManager
{
    private $statsToCheck;
    private $slaConfigManager;
    private $loggingConfigManager;
    private $pageDao;
    private $loadTimeSummaryDao;
    private $notifier;
    private $activityLogger;

    public function __construct(
        $slaConfigManager,
        $loggingConfigManager,
        $pageDao,
        $loadTimeSummaryDao,
        $notifier,
        $activityLogger
    ) {
        $this->statsToCheck = [
            'ttfb' => 'TTFB',
            'loadTime' => 'Load Time'
        ];
        $this->slaConfigManager = $slaConfigManager;
        $this->loggingConfigManager = $loggingConfigManager;
        $this->pageDao = $pageDao;
        $this->loadTimeSummaryDao = $loadTimeSummaryDao;
        $this->notifier = $notifier;
        $this->activityLogger = $activityLogger;
    }

    public function notify($until) {
        $appAndDomainNames = $this->loggingConfigManager->getAppAndDomainNames('boomerang');
        $appIdsWithBreaches = [];
        foreach ($appAndDomainNames as $appId=>$appDetails) {
            list($hasSlaBeenBreached, $breaches, $contact) = $this->checkBreach($appDetails, $until);
            if ($hasSlaBeenBreached) {
                $appIdsWithBreaches[] = $appId;
                $this->sendNotification($appId, $breaches, $contact);
            }
        }
        return $appIdsWithBreaches;
    }

    private function checkBreach($appDetails, $until) {
        $slaConfig = $this->slaConfigManager->getConfig($appDetails['appId']);
        $slaGlobalConfig = $this->slaConfigManager->getGlobalConfig();

        $breaches = ['breaches' => []];
        $contact = [];
        if (!empty($slaConfig)) {
            $contact = $this->fixMissingContactConfig($slaConfig['contact'], $slaGlobalConfig);
            foreach ($slaConfig['sla'] as $tag=>$sla) {
                $sla = $this->fixMissingSlaConfig($sla, $slaGlobalConfig);
                $from = $until - intval($sla['breachInterval'])*60*60;
                $performanceStats = $this->getPerformanceStats($appDetails, $tag, $from, $until);
                $currentBreaches = $this->checkBreachForPerfomanceStats($sla, $performanceStats);
                if (!empty($currentBreaches)) {
                    $breaches['breaches'][$tag] = $currentBreaches;
                    $breaches['domainName'] = $appDetails['domainName'];
                    $breaches['appName'] = $appDetails['appName'];
                }
            }

            $breaches = $this->filterBreachesBelowAllowedBreachCount($breaches);
        }
        $hasSlaBeenBreached = !empty($breaches['breaches']);

        return [
            $hasSlaBeenBreached,
            $breaches,
            $contact
        ];
    }

    private function fixMissingContactConfig($contact, $slaGlobalConfig) {
        $fixedConfig = $contact;
        if (!isset($contact['slack'])) {
            $fixedConfig['slack'] = $slaGlobalConfig['contact']['slack'];
        }
        return $fixedConfig;
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

    private function getPerformanceStats($appDetails, $tag, $from, $until) {
        $pageId = $this->pageDao->getByTag($appDetails['appId'], $tag);

        $lastEvenYear = (date('Y') % 2 === 0) ? date('Y') : date('Y') - 1;
        $lastEvenYearTimestamp = mktime(0, 0, 0, 1, 1, $lastEvenYear);
        $startHoursElapsedSinceLastEvenYear = floor(($from-$lastEvenYearTimestamp) / 3600);
        $endHoursElapsedSinceLastEvenYear = floor(($until-$lastEvenYearTimestamp) / 3600);
        return $this->loadTimeSummaryDao->getByPageId(
            $appDetails['appId'],
            $pageId,
            $startHoursElapsedSinceLastEvenYear,
            $endHoursElapsedSinceLastEvenYear
        );
    }

    private function checkBreachForPerfomanceStats($sla, $performanceStats) {
        $breaches = [];
        foreach ($performanceStats as $performanceStat) {
            $breaches = array_merge($breaches, $this->checkBreachForPerfomanceStat($sla, $performanceStat));
        }
        return $breaches;
    }

    private function checkBreachForPerfomanceStat($sla, $performanceStat) {
        $breaches = [];
        foreach ($this->statsToCheck as $statShortName=>$statLongName) {
            if ($performanceStat[$statShortName] > $sla[$statShortName]) {
                $breaches[] = [
                    'stat' => $statLongName,
                    'allowed' => $sla[$statShortName].' ms',
                    'observed' => $performanceStat[$statShortName].' ms',
                    'when' => date('H', $from + 3600*$performanceStat['hourOfDay']).'00hrs'
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

    private function sendNotification($appId, $breaches, $contact) {
        $messageParts = [
            '*SLA breached in '
                .$breaches['domainName']
                .' '.($breaches['appName'] ? $breaches['appName'] : $appId)
                ."*\n"
        ];
        foreach ($breaches['breaches'] as $tag=>$tagWiseBreaches) {
            $breachCount = count($tagWiseBreaches);
            $messageParts[] = ">*$tag*: for $breachCount hrs\n";
            $tagWiseHighestBreaches = [];
            foreach ($tagWiseBreaches as $breach) {
                if ($breach['observed'] > $tagWiseHighestBreaches[$breach['stat']]['observed']) {
                    $tagWiseHighestBreaches[$breach['stat']] = $breach;
                }
            }
            foreach ($tagWiseHighestBreaches as $breach) {
                $messageParts[] = '>'.$breach['stat'].': *'
                    .$breach['observed'].'* _(allowed: '.$breach['allowed'].')_ @ '.$breach['when']."\n";
            }
        }
        // $messageParts[] = '________________________________';
        $message = implode('', $messageParts);

        $this->notifier->sendMessage(
            'NewMonk',
            $contact['slack']['webhookUrl'],
            $contact['slack']['channel'],
            $message,
            [
                'icon_emoji' => ':chart_with_upwards_trend:'
            ]
        );
        $this->activityLogger->log($message);
    }
}

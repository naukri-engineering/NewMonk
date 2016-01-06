<?php

namespace NewMonk\lib\error\log;

class QueueProcessor
{
    const BATCH_COUNT = 1000;
    private $queueErrorLogger;
    private $validatorFactory;
    private $middlewareChain;
    private $logBuilder;
    private $dataManager;
    private $activityLogger;

    public function __construct(
        $queueErrorLogger,
        $validatorFactory,
        $middlewareChain,
        $logBuilder,
        $dataManager,
        $activityLogger
    ) {
        $this->queueErrorLogger = $queueErrorLogger;
        $this->validatorFactory = $validatorFactory;
        $this->middlewareChain = $middlewareChain;
        $this->logBuilder = $logBuilder;
        $this->dataManager = $dataManager;
        $this->activityLogger = $activityLogger;
    }

    public function processNextBatch() {
        $errorLogs = [];
        for ($processedLogsCount=0; $processedLogsCount < self::BATCH_COUNT; ) {
            $stringifiedErrorLogs = $this->queueErrorLogger->getNextLog();
            $isQueueEmpty = empty($stringifiedErrorLogs);
            if ($isQueueEmpty) {
                break;
            }

            list($currentErrorLogs, $currentProcessedLogsCount) = $this->processCurrentBatch($stringifiedErrorLogs);
            $processedLogsCount += $currentProcessedLogsCount;
            $errorLogs = array_merge($errorLogs, $currentErrorLogs);
        }

        if (!empty($errorLogs)) {
            $this->dataManager->save($errorLogs);
            $this->logProcessingActivity($processedLogsCount);
        }

        return $processedLogsCount;
    }

    private function processCurrentBatch($stringifiedErrorLogs) {
        $unfilteredErrorLogs = json_decode($stringifiedErrorLogs, true);
        $filteredErrorLogs = $this->validatorFactory->getValidator($unfilteredErrorLogs)->filter($unfilteredErrorLogs);
        $this->logFilteringActivity($stringifiedErrorLogs, $unfilteredErrorLogs, $filteredErrorLogs);
        $errorLogs = $this->preProcessLogs($filteredErrorLogs);
        return $this->logBuilder->build($errorLogs);
    }

    private function logFilteringActivity($stringifiedErrorLogs, $unfilteredErrorLogs, $filteredErrorLogs) {
        $invalidLogsCount = $this->getInvalidLogsCount(
            $stringifiedErrorLogs,
            $unfilteredErrorLogs,
            $filteredErrorLogs
        );
        if ($invalidLogsCount > 0) {
            $this->activityLogger->log("Discarded $invalidLogsCount invalid logs: ".$stringifiedErrorLogs);
        }
    }

    private function getInvalidLogsCount($stringifiedErrorLogs, $unfilteredErrorLogs, $filteredErrorLogs) {
        $unfilteredLogsCount = $this->getLogsCount($unfilteredErrorLogs);
        if ($unfilteredLogsCount == 0 && !empty($stringifiedErrorLogs)) {
            $invalidLogsCount = 1;
        } else {
            $invalidLogsCount = $unfilteredLogsCount - $this->getLogsCount($filteredErrorLogs);
        }

        return $invalidLogsCount;
    }

    private function getLogsCount($errorLogs) {
        return is_array($errorLogs['exceptions']) ? count($errorLogs['exceptions']) : 0;
    }

    private function preProcessLogs($errorLogs) {
        return $this->middlewareChain->run($errorLogs);
    }

    private function logProcessingActivity($processedLogsCount) {
        $hasProcessed = $processedLogsCount > 0;
        if ($hasProcessed) {
            $this->activityLogger->log("Processed $processedLogsCount logs");
        }
    }
}

<?php

namespace NewMonk\batch;

require_once __DIR__.'/../config/config.php';

use NewMonk\lib\error\log\QueueProcessorFactory;

function daemonize($errorConfigFilePath, $elasticSearchConfigFilePath) {
    $errorLogQueueProcessor = QueueProcessorFactory::getInstance()->getQueueProcessor(
        $errorConfigFilePath,
        $elasticSearchConfigFilePath,
        LOG_DIR
    );
    while (true) {
        $processedLogsCount = $errorLogQueueProcessor->processNextBatch();
        $hasProcessed = $processedLogsCount > 0;
        if (!$hasProcessed) {
            sleep(5);
            continue;
        }
    }
}

exec('ps ax | grep '.escapeshellarg(basename(__FILE__)), $output);
if (count($output) >= 4) {
    echo "An instance of this script is already running!\n";
    exit(0);
}

daemonize($errorConfigFilePath, $elasticSearchConfigFilePath);

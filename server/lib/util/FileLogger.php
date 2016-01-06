<?php

namespace NewMonk\lib\util;

class FileLogger implements ILogger
{
    private $name;
    private $logDirPath;
    private $options;
    private $currentDate;
    private $logFilePath;
    private $defaultOptions = ['rotate' => true];

    public function __construct($name, $logDirPath, $options) {
        $this->name = $name;
        $this->logDirPath = $logDirPath;
        $this->options = array_merge($this->defaultOptions, $options);
        $this->resetLogFilePathAndCurrentDate();
    }

    private function resetLogFilePathAndCurrentDate() {
        $this->currentDate = date('Y-m-d');
        $this->logFilePath = $this->logDirPath.'/'.$this->name.'_'.$this->currentDate.'.log';
    }

    public function log($message) {
        $this->updateLogFilePathIfRequired();
        file_put_contents($this->logFilePath, date('[Y-m-d H:i:s] ').$message."\n", FILE_APPEND);
    }

    private function updateLogFilePathIfRequired() {
        $shouldLogFilePathBeUpdated = ($this->options['rotate'] === true) && ($this->currentDate != date('Y-m-d'));
        if ($shouldLogFilePathBeUpdated) {
            $this->resetLogFilePathAndCurrentDate();
        }
    }
}

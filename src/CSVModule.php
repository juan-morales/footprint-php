<?php

namespace Footprint;

use Footprint\Interfaces\IModule;

class CSVModule implements IModule
{
    const MODULE_ID = "CSV";
    protected $fileHandler;

    public function __construct(string $filename) {
        if (!$this->fileHandler = fopen($filename, "a+")) {
            return;
        }
    }

    public function getId() {
        return self::MODULE_ID;
    }

    public function onInit(Tracker &$tracker) {
        fputcsv($this->fileHandler, ["Initialization", "OK"]);
    }

    public function onEnd(Tracker &$tracker) {
        fputcsv($this->fileHandler, ["End", "OK"]);
        fclose($this->fileHandler);
    }

    public function onLoad(Tracker &$tracker) {
        fputcsv($this->fileHandler, ["Load", "OK"]);
    }

    public function onUnload(Tracker &$tracker) {
        fputcsv($this->fileHandler, ["Unload", "OK"]);
    }

    public function onLog(Tracker &$tracker) {
        $data1 = $tracker->getLogDataByKey("CSV_key1");
        $data2 = $tracker->getLogDataByKey("CSV_key2");
        fputcsv($this->fileHandler, [$tracker->getLogId(), $data1, $data2]);
    }

    public function onLogBuild(Tracker &$tracker) {
        $tracker->addLogData("CSV_key1", "CSV_data1");
        $tracker->addLogData("CSV_key2", "CSV_data2");
    }
}
<?php

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class CSVModule implements IModule
{
    const MODULE_ID = "CSV_Module";
    protected $fileHandler;

    public function __construct(string $filename) {
        if (!$this->fileHandler = fopen($filename, "w+")) {
            return;
        }
    }

    public function getId() {
        return self::MODULE_ID;
    }

    public function onInit(Tracker &$tracker) {
        return;
    }

    public function onEnd(Tracker &$tracker) {
        fclose($this->fileHandler);
        return;
    }

    public function onLoad(Tracker &$tracker) {
        return;
    }

    public function onUnload(Tracker &$tracker) {
        return;
    }

    public function onLog(Tracker &$tracker) {
        fputcsv(
            $this->fileHandler,
            [$tracker->getLogId(), $tracker->getLogDataAsJSON()],
            ",",
            "'"
        );
    }

    public function onLogBuild(Tracker &$tracker) {
        return;
    }
}
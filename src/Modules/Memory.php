<?php

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class Memory implements IModule
{
    const MODULE_ID = "MEMORY";
    
    public function getId() {
        return self::MODULE_ID;
    }

    public function onInit(Tracker &$tracker) {
        return;
    }

    public function onEnd(Tracker &$tracker) {
        return;
    }

    public function onLoad(Tracker &$tracker) {
        return;
    }

    public function onUnload(Tracker &$tracker) {
        return;
    }

    public function onLog(Tracker &$tracker) {
        return;
    }

    public function onLogBuild(Tracker &$tracker) {
        $tracker->addLogData($this->getId()."_memory_usage_bytes", memory_get_usage());
        $tracker->addLogData($this->getId()."_memory_real_usage_bytes", memory_get_usage(true));
        $tracker->addLogData($this->getId()."_memory_peak_bytes", memory_get_peak_usage());
        $tracker->addLogData($this->getId()."_memory_real_peak_bytes", memory_get_peak_usage(true));
    }
}
<?php

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class Memory implements IModule
{
    const MODULE_ID = "MEMORY";
    const KEY_MEM_USAGE = self::MODULE_ID."_memory_usage";
    const KEY_MEM_REAL_USAGE = self::MODULE_ID."_memory_real_usage";
    const KEY_MEM_PEAK = self::MODULE_ID."_memory_peak";
    const KEY_MEM_REAL_PEAK = self::MODULE_ID."_memory_real_peak";

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
        $tracker->addLogData(self::KEY_MEM_USAGE, memory_get_usage());
        $tracker->addLogData(self::KEY_MEM_REAL_USAGE, memory_get_usage(true));
        $tracker->addLogData(self::KEY_MEM_PEAK, memory_get_peak_usage());
        $tracker->addLogData(self::KEY_MEM_REAL_PEAK, memory_get_peak_usage(true));
    }

    public function getKeys() : array {
        return [
            self::KEY_MEM_USAGE,
            self::KEY_MEM_REAL_USAGE,
            self::KEY_MEM_PEAK,
            self::KEY_MEM_REAL_PEAK,
        ];
    }
}
<?php declare(strict_types=1);

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class Time implements IModule
{
    const MODULE_ID = "TIME";
    const KEY_TOTAL_TIME = self::MODULE_ID."_total";
    const KEY_STEP_TIME = self::MODULE_ID."_step";

    protected $totalTime = 0;
    protected $microtime = null;
    protected $previousStep = null;
    protected $currentStep = null;

    public function getId() {
        return self::MODULE_ID;
    }

    public function onInit(Tracker &$tracker) {
        $this->microtime = microtime(true);
        $this->previousStep = $this->microtime;
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
        $now = microtime(true);
        
        $this->totalTime += round(($now - $this->microtime), 2);
        $this->microtime = $now;
        
        $this->currentStep = round(($now - $this->previousStep), 2);
        $this->previousStep = $now;
        
        $tracker->addLogData(self::KEY_TOTAL_TIME, (string)$this->totalTime);
        $tracker->addLogData(self::KEY_STEP_TIME, (string)$this->currentStep);
    }

    public function getKeys() : array {
        return [
            self::KEY_TOTAL_TIME,
            self::KEY_STEP_TIME,
        ];
    }
}
<?php

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class Time implements IModule
{
    const MODULE_ID = "TIME";
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
        
        $tracker->addLogData($this->getId()."_total_seconds", (string)$this->totalTime);
        $tracker->addLogData($this->getId()."_step_seconds", (string)$this->currentStep);
    }
}
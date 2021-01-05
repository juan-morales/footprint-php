<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Modules\Time;

class TimeModuleTest extends TestCase 
{
    public function testModule() {
        $tracker = new Tracker();
        $module = new Time();
        $tracker->loadModule($module);
        $tracker->init();
        $tracker->log();
        $logDataStep1 = $tracker->getLogData();
        $tracker->log();
        $logDataStep2 = $tracker->getLogData();
        $tracker->log();
        $logDataStep3 = $tracker->getLogData();
        $tracker->end();

        $this->assertArrayHasKey($module->getId()."_total_seconds", $logDataStep1);
        $this->assertArrayHasKey($module->getId()."_step_seconds", $logDataStep1);

        $this->assertArrayHasKey($module->getId()."_total_seconds", $logDataStep2);
        $this->assertArrayHasKey($module->getId()."_step_seconds", $logDataStep2);

        $this->assertArrayHasKey($module->getId()."_total_seconds", $logDataStep3);
        $this->assertArrayHasKey($module->getId()."_step_seconds", $logDataStep3);
    }
}

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

        foreach($module->getKeys() as $key) {
            $this->assertArrayHasKey($key, $logDataStep1);
            $this->assertArrayHasKey($key, $logDataStep2);
            $this->assertArrayHasKey($key, $logDataStep3);
        }
    }
}

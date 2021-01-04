<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Modules\Memory;

class MemoryModuleTest extends TestCase 
{
    public function testModule() {
        $tracker = new Tracker();
        $module = new Memory();
        $tracker->loadModule($module);
        $tracker->init();
        $tracker->log();
        $logData = $tracker->getLogData();
        $tracker->end();

        $this->assertTrue(array_key_exists($module->getId()."_memory_usage_bytes", $logData));
        $this->assertTrue(array_key_exists($module->getId()."_memory_real_usage_bytes", $logData));
        $this->assertTrue(array_key_exists($module->getId()."_memory_peak_bytes", $logData));
        $this->assertTrue(array_key_exists($module->getId()."_memory_real_peak_bytes", $logData));
    }
}

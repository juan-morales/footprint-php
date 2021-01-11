<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Modules\ChartJS;
use Footprint\Modules\Memory;
use Footprint\Modules\Time;

class ChartsJSModuleTest extends TestCase 
{
    public function testModule() {
        $mem = str_repeat("X", 1024 * 1024);
        
        $moduleTime = new Time();
        $moduleMem = new Memory();

        $module = new ChartJS();
        $module->addKey("MEMORY_memory_usage_bytes");
        $module->addKey("MEMORY_memory_peak_bytes");
        $module->addKey("TIME_total_seconds");
        $module->addKey("TIME_step_seconds");

        $tracker = new Tracker();
        $tracker->loadModule($moduleMem);
        $tracker->loadModule($moduleTime);
        $tracker->loadModule($module);
        $tracker->init();
        $tracker->log();
        $mem = str_repeat($mem, 2);
        sleep(1);
        $tracker->log();
        sleep(2);
        $mem = str_repeat($mem, 2);
        $tracker->log();
        sleep(2);
        $mem = str_repeat("XXX", 2000);
        $tracker->log();
        $tracker->end();
    }
}

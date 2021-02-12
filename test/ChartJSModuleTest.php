<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Modules\ChartJS;
use Footprint\Modules\Memory;
use Footprint\Modules\Time;

class ChartJSModuleTest extends TestCase 
{
    public function testModule() {
        @unlink("test/files/chartjs.html");

        $mem = str_repeat("X", 1024 * 1024);
        
        $moduleTime = new Time();
        $moduleMem = new Memory();

        $module = new ChartJS("test/files/chartjs.html");

        foreach($moduleMem->getKeys() as $key) {
            $module->addKey($key);
        }

        foreach($moduleTime->getKeys() as $key) {
            $module->addKey($key);
        }
        
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
        sleep(1);
        $mem = str_repeat("XXX", 2000);
        $tracker->log();
        $tracker->end();

        $this->assertFileExists("test/files/chartjs.html");
    }
}

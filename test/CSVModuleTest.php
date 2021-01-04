<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Modules\CSV;

class CSVModuleTest extends TestCase 
{
    public function testModule() {
        $tracker = new Tracker();
        $module = new CSV("test/tmpCSVModule");
        $tracker->loadModule($module);
        $tracker->init();
        $tracker->log();
        $tracker->log();
        $tracker->log();
        $tracker->end();

        $this->assertFileExists("test/tmpCSVModule");
        
        $fileHandler = fopen("test/tmpCSVModule", "r");

        while ($data = fgetcsv($fileHandler, 0, ",", "'")) {
            $this->assertCount(2, $data);
            $this->assertJsonStringEqualsJsonString(
                json_encode([]),
                $data[1]
            );
            $this->assertMatchesRegularExpression('/\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d_.*/', $data[0]);
        }
    }
}

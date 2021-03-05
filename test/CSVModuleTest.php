<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Modules\CSV;

class CSVModuleTest extends TestCase 
{
    public function testModule() {
        @unlink("test/files/tmpCSVModule");

        $tracker = new Tracker();
        $module = new CSV("test/files/tmpCSVModule");
        $tracker->loadModule($module);
        
        $tracker->onLogBuild(function ($logId, $logData) use ($tracker) {
            $tracker->addLogData("testKey", "testData");
        });
        
        $tracker->init();
        $tracker->log();
        $tracker->log();
        $tracker->log();
        $tracker->end();

        $this->assertFileExists("test/files/tmpCSVModule");
        
        $fileHandler = fopen("test/files/tmpCSVModule", "r");

        while ($data = fgetcsv($fileHandler, 0, ",", "'")) {
            $this->assertCount(2, $data);
            $this->assertJsonStringEqualsJsonString(
                json_encode(["testKey" => "testData"]),
                $data[1]
            );
            $this->assertMatchesRegularExpression('/\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d_.*/', $data[0]);
        }
    }
}

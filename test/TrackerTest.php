<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Footprint\Tracker;
use Footprint\Interfaces\IModule;

class TrackerTest extends TestCase 
{
    public function testTrackerCreation() {
        $tracker = new Tracker();
        $this->assertInstanceOf(Tracker::class, $tracker);
    }

    public function testDataHandling() {
        $tracker = new Tracker();
        $tracker->setData("key1", "data1");
        $data = $tracker->getData("key1");
        $this->assertEquals("data1", $data);
        $tracker->deleteData("key1");
        $data = $tracker->getData("key1");
        $this->assertNull($data);
    }

    public function testOnLogCallbacks() {
        $tracker = new Tracker();
        
        $tracker->onLog(function (string $key, array $data) use ($tracker) {
            $tracker->setData("key1", "data1");
        });

        $tracker->onLog(function (string $key, array $data) use ($tracker) {
            $tracker->setData("key2", "data2");
        });

        $tracker->log();

        $this->assertEquals("data1", $tracker->getData("key1"));
        $this->assertEquals("data2", $tracker->getData("key2"));
    }

    public function testOnInitCallbacks() {
        $tracker = new Tracker();
        
        $tracker->onInit(function (Tracker $tracker) {
            $tracker->setData("key1", "data1");
        });

        $tracker->onInit(function (Tracker $tracker) {
            $tracker->setData("key2", "data2");
        });

        $tracker->init();

        $this->assertEquals("data1", $tracker->getData("key1"));
        $this->assertEquals("data2", $tracker->getData("key2"));
    }

    public function testOnEndCallbacks() {
        $tracker = new Tracker();
        
        $tracker->onEnd(function (Tracker $tracker) {
            $tracker->setData("key1", "data1");
        });

        $tracker->onEnd(function (Tracker $tracker) {
            $tracker->setData("key2", "data2");
        });

        $tracker->end();

        $this->assertEquals("data1", $tracker->getData("key1"));
        $this->assertEquals("data2", $tracker->getData("key2"));
    }

    public function testModuleHandling() {
        $tracker = new Tracker();
        $buffer = [];

        $module = new class($buffer) implements IModule {
            protected $buffer;

            public function __construct(&$buffer) {
                $this->buffer = &$buffer;
            }

            public function getId() {
                return "TEST";
            }

            public function onInit(Tracker &$tracker) {
                $tracker->setData("TEST_init1", "TEST_init1_data");
                $tracker->setData("TEST_init2", "TEST_init2_data");
                $tracker->setData("TEST_counter", "0");
            }

            public function onEnd(Tracker &$tracker) {
                $tracker->setData("TEST_end1", "TEST_end1_data");
                $tracker->setData("TEST_end2", "TEST_end2_data");
            }

            public function onLoad(Tracker &$tracker) {
                $tracker->setData("TEST_load", "TEST_OK");
            }

            public function onUnload(Tracker &$tracker) {
                $tracker->setData("TEST_unload", "TEST_OK");
            }

            public function onLog(Tracker &$tracker) {
                $tracker->setData("TEST_counter", (string)((int)($tracker->getData("TEST_counter")) + 1));
                $this->buffer[$tracker->getLogId()] = [
                    "TEST_logData1" => $tracker->getLogDataByKey("TEST_logData1"),
                    "TEST_logData2" => $tracker->getLogDataByKey("TEST_logData2"),
                ];
            }

            public function onLogBuild(Tracker &$tracker) {
                $tracker->addLogData("TEST_logData1", "TEST_logData_data1");
                $tracker->addLogData("TEST_logData2", "TEST_logData_data2");
            }
        };

        $tracker->loadModule($module);

        $tracker->init();
        $tracker->log();
        $tracker->log();
        $tracker->log();
        $tracker->end();

        $this->assertEquals(3, count($buffer));
        $this->assertEquals("TEST_init1_data", $tracker->getData("TEST_init1"));
        $this->assertEquals("TEST_init2_data", $tracker->getData("TEST_init2"));
        $this->assertEquals("3", $tracker->getData("TEST_counter"));
        $this->assertEquals("TEST_end1_data", $tracker->getData("TEST_end1"));
        $this->assertEquals("TEST_end2_data", $tracker->getData("TEST_end2"));
        $this->assertEquals("TEST_OK", $tracker->getData("TEST_load"));
        $this->assertEquals("TEST_OK", $tracker->getData("TEST_unload"));
    }
}

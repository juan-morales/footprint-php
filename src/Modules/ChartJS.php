<?php

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class ChartJS implements IModule
{
    const MODULE_ID = "CHARTJS";
    protected $keys = [];
    protected $data = [];
    protected $labels = [];

    public function __construct(array $keys = []) {
        $this->keys = $keys;
    }

    public function getId() {
        return self::MODULE_ID;
    }

    public function onInit(Tracker &$tracker) {
        return;
    }

    public function onEnd(Tracker &$tracker) {
        $colors = [
            "red",
            "blue",
            "green",
            "black",
            "purple",
            "orange"
        ];
        $colorIndex = 0;

        $labels = json_encode($this->labels);
        $datasetTemplate = "{
            label: '{{label}}',
            data: {{data}},
            borderColor: '{{color}}',
            pointBackgroundColor: '{{color}}',
            borderWidth: 1,
            fill: false,
            lineTension: 0.1
        },";
        
        $data = "";

        foreach($this->keys as $key) {
            $tmp = str_replace("{{label}}", $key, $datasetTemplate);
            $tmp = str_replace("{{data}}", json_encode($this->data[$key]), $tmp);
            $tmp = str_replace("{{color}}", $colors[$colorIndex], $tmp);
            $data .= $tmp;

            $colorIndex++;

            if ($colorIndex == count($colors) - 1) {
                $colorIndex = 0;
            } 
        }

        $data[-1] = \chr(32);

        $myData = "labels: {{labels}},
        datasets:[
            {{data}}
        ],";

        $myData = str_replace("{{labels}}", $labels, $myData);
        $myData = str_replace("{{data}}", $data, $myData);

        var_dump($myData);

        return;
    }

    public function onLoad(Tracker &$tracker) {
        return;
    }

    public function onUnload(Tracker &$tracker) {
        return;
    }

    public function onLog(Tracker &$tracker) {
        $this->labels[] = explode("_", $tracker->getLogId())[0];

        foreach ($this->keys as $key) {
            $this->data[$key][] = $tracker->getLogDataByKey($key);
        }

        return;
    }

    public function onLogBuild(Tracker &$tracker) {
        return;
    }

    public function addKey(string $key) {
        $this->keys[] = $key;
        return $this;
    }

    public function removeKey(string $key) {
        unset($this->keys[$key]);
        return $this;
    }
}
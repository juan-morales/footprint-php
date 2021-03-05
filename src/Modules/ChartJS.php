<?php declare(strict_types=1);

namespace Footprint\Modules;

use Footprint\Interfaces\IModule;
use Footprint\Tracker;

class ChartJS implements IModule
{
    const MODULE_ID = "CHARTJS";
    protected $keys = [];
    protected $data = [];
    protected $labels = [];
    protected $fileHandler;

    public function __construct(string $filename, array $keys = []) {
        if (!$this->fileHandler = fopen($filename, "w+")) {
            throw new Exception("Could not create file ${$filename}");
        }

        $this->keys = $keys;
    }

    public function getId() {
        return self::MODULE_ID;
    }

    public function onInit(Tracker &$tracker) {
        return;
    }

    public function onEnd(Tracker &$tracker) {
        $reportTemplate = '
        <!DOCTYPE html>
        <html>
            <head>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js"></script>
            </head>
            <body>
                <div style="width: 100%; height: 30%;">
                    <canvas id="myChart"></canvas>
                </div>
                
                <script>
                    let ctx = document.getElementById("myChart").getContext("2d");
                    
                    let myData = {
                        labels: {{labels}},
                        datasets:[
                            {{data}}
                        ],
                    };

                    let myOptions = {
                        tooltips: {
                            mode: "index",
                            intersect: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                }
                            }],
                            xAxes: [{
                                type: "time",
                                distribution: "series",
                                ticks: {
                                    source: "auto",
                                    autoSkip: true
                                },
                                time: {
                                    unit: "second",
                                    stepSize: 1,
                                }
                            }]
                        }
                    };

                    let myChart = new Chart(ctx, {
                        type: "line",
                        data: myData,
                        options: myOptions
                    });
                </script>
            </body>
        </html>';

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

        $reportTemplate = str_replace("{{labels}}", $labels, $reportTemplate);
        $reportTemplate = str_replace("{{data}}", $data, $reportTemplate);

        fwrite($this->fileHandler, $reportTemplate);
        fclose($this->fileHandler);

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

    public function getKeys() : array {
        return [];
    }
}
# footprint-php

Composer package that allows you to closely monitor and retrieve custom stats about your running php script.

Its build in a modular way handled by event triggers, really easy to follow and customize.

The package already come with some modules likes:

-   ChartJS line chart report
-   CSV report
-   Time-tracking
-   Memory tracking

Code is really simple and short, so you can quickly pick it up and create your own code.

# Quick intro

## Installation

```
composer require jcm/footprint-php
```

## Usage example and output

In this code we want to measure how much time and memory this php script uses.


```php
<?php declare(strict_types = 1);

use Footprint\Tracker;
use Footprint\Modules\ChartJS;
use Footprint\Modules\Memory;
use Footprint\Modules\Time;

class SomeClassName 
{
    public function testMethod() {
        //we create a variable using certain memory
        $mem = str_repeat("X", 1024 * 1024);
        
        //create instances of the Time and Memory modules
        $moduleTime = new Time();
        $moduleMem = new Memory();
        
        //create instance of teh CHartJS module and we specify an output file (report)
        $moduleChartJS = new ChartJS("report.html");

        //set the keys that will be use in the chart by $moduleChartJS
        foreach($moduleMem->getKeys() as $key) {
            $moduleChartJS->addKey($key);
        }

        foreach($moduleTime->getKeys() as $key) {
            $moduleChartJS->addKey($key);
        }
        
        //tracker instance
        $tracker = new Tracker();
        
        //load the previous modules into the tracker
        $tracker->loadModule($moduleMem);
        $tracker->loadModule($moduleTime);
        $tracker->loadModule($moduleChartJS);
        
        //start
        $tracker->init();
        
        //first log
        $tracker->log();
        
        //we increase the memory used by he script
        $mem = str_repeat($mem, 2);
        
        //wait 1 second
        sleep(1);
        
        //and log again
        $tracker->log();
        
        //wait 2 seconds
        sleep(2);
        
        //increae the memory
        $mem = str_repeat($mem, 2);
        
        //log again
        $tracker->log();
        
        //and so on
        sleep(1);
        $mem = str_repeat("XXX", 2000);
        $tracker->log();
        
        //finally we finish the track
        $tracker->end();
        
        //and we should hae a file called report.html that should look something like the next screenshot
    }
}
```

![Output report using ChartJS](https://github.com/juan-morales/footprint-php/blob/main/output_example.jpg "Output with ChartJS")


# Why this package?

This package was created in order to have a simple and small package, customizable, that could allow you to retrieve data while you execute your php scripts.

# Documentation

Please refer to the wiki section of the project to learn more about the package design, usage, and how to extend it.

# Contributing to the project

This is a project I have done in order to creat

# Autor

Juan Carlos Morales <jcmargentina@gmail.com>

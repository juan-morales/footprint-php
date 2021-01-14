# footprint-php

[![Latest Stable Version](https://poser.pugx.org/jcm/footprint-php/v)](//packagist.org/packages/jcm/footprint-php) [![Total Downloads](https://poser.pugx.org/jcm/footprint-php/downloads)](//packagist.org/packages/jcm/footprint-php) [![Latest Unstable Version](https://poser.pugx.org/jcm/footprint-php/v/unstable)](//packagist.org/packages/jcm/footprint-php) [![License](https://poser.pugx.org/jcm/footprint-php/license)](//packagist.org/packages/jcm/footprint-php)

Composer package that allows you to trace/monitor and retrieve/calculate/generate custom stats about your running php script.

It is build in a modular way and event triggers, really easy to follow and customize.

The package already comes with some modules likes:

-   ChartJS line chart report (output only)
-   CSV report (log and output)
-   Time tracking (log)
-   Memory tracking (log)

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
        
        /**
        * Then in another file, create an instance of this class, call the testMethod() method
        * and we should have a file called report.html that should look something like the next screenshot
        */
    }
}
```

![Output report using ChartJS](https://github.com/juan-morales/footprint-php/blob/main/output_example.jpg "Output with ChartJS")


# Why this package?

This package was created in order to have a simple and small package, customizable, that could allow you to retrieve data while you execute your php scripts.

# Documentation (Work in progress)

Please refer to the wiki section of the project to learn more about the package design, usage, and how to extend it.

# Contributing to the project

Even though I am working on this package in order to improve it, feel free to open issues, send pull requests, creating new modules or enhancing the existing ones, etc. 

It is possible to create advance modules (like a live dashboard with websocket connection to update data on it, so we can have a real-time dashboard, etc.), but for the first release of this package I wanted to keep it simple and functional.

I am thinking about decouple the modules and put them in different repositories, but ... maybe version 2.

**All kind of help is welcome**

# Autor

Juan Carlos Morales <jcmargentina@gmail.com>

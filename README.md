# footprint-php

![Latest Stable Version](https://img.shields.io/packagist/v/jcm/footprint-php)
![Latest Release Version](https://img.shields.io/github/v/release/juan-morales/footprint-php)
![Total Downloads](https://img.shields.io/packagist/dt/jcm/footprint-php)
![License](https://img.shields.io/packagist/l/jcm/footprint-php)

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

## Step by Step usage example and output

In this code we want to measure how much time and memory a function call or class method uses.


Keep in mind that is a demo example, **you will not have to initialize more than once if you code properly**.

Basically in the example we create a class called `SomeClassName` with a method called `testMethod` and we are gonna measure time and memory usage of this method when its called.

We will do this example by creating a php project from scratch, if you already have a project, adjust the example to your needs.

#### Step 1: Create a php project with composer

Run `composer init` and create a **project** , name it as you want.

While the wizard asks you different questions about the project, it will ask you about what dependencies you want to install, leave it blank for now.

It looks something like this:
`Would you like to define your dependencies (require) interactively [yes]? no` answer no.

#### Step 2: Install footprint-php package

Run `composer require jcm/footprint-php`

You should get an output like this:

```
Using version ^1.1 for jcm/footprint-php
./composer.json has been updated
Running composer update jcm/footprint-php
Loading composer repositories with package information
Updating dependencies
Lock file operations: 1 install, 0 updates, 0 removals
  - Locking jcm/footprint-php (v1.1)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 1 install, 0 updates, 0 removals
  - Installing jcm/footprint-php (v1.1): Extracting archive
Generating autoload files
```

#### Step 4: Create an index.php with our code

Run `touch index.php`

With the following code inside

```php
<?php declare(strict_types = 1);

require("vendor/autoload.php");

use Footprint\Tracker;             //For the main Tracker instance
use Footprint\Modules\ChartJS;     //Tracker module
use Footprint\Modules\Memory;      //Tracker module
use Footprint\Modules\Time;        //Tracker module

class SomeClassName 
{
    public function testMethod() {
        $mem = str_repeat("X", 1024 * 1024); //We create a variable using certain memory size
        
        $moduleTime = new Time();  //Create instance of the Time module
        $moduleMem = new Memory(); //Create instance of the Memory module
        
        /**
         * We create an instance of the ChartJS module
         * and we also specify the name of the output file that it will generate for us
         */
        $moduleChartJS = new ChartJS("report.html");

        /**
         * Module ChartJS needs to know in advance which "Tracker keys" will
         * consider at the moment of generating the chart for us.
         * 
         * You can read more about this in the Wiki section, but basically we are adding all
         * the keys used in the Memory and Time modules.
         */
        foreach($moduleMem->getKeys() as $key) {
            $moduleChartJS->addKey($key);
        }

        foreach($moduleTime->getKeys() as $key) {
            $moduleChartJS->addKey($key);
        }
        
        $tracker = new Tracker(); //Main Tracker instance
        
        /**
         * We load the previously created modules into the Tracker.
         * 
         * The Tracker is the main actor in all this scenario.
         * 
         * The Tracker will use the modules properly.
         */
        $tracker->loadModule($moduleMem);
        $tracker->loadModule($moduleTime);
        $tracker->loadModule($moduleChartJS);
        
        $tracker->init(); //Start the Tracker
        
        $tracker->log(); //We do our first log
        
        /**
         * Now we will ...
         * 1) Increase the memory use by the $mem variable
         * 2) Make some sleeps to generate a delay
         * 
         * All these is done trying to simulate a real php code execution, that uses
         * different amount of memory and time execution.
         * 
         */

        $mem = str_repeat($mem, 2); //We duplicate the size of memory use by variable $mem
        
        sleep(1); //Wait 1 second
        
        $tracker->log(); //Log again
        
        sleep(2);
        
        $mem = str_repeat($mem, 2);
        
        $tracker->log();
        
        sleep(1);
        
        $mem = str_repeat("XXX", 2000);
        
        $tracker->log();
        
        /**
         * When we get to the point we dont want to track anymore, then we finalize tracking calling
         * the end() method of the Tracker.
         */
        $tracker->end();
    }
}

$testClass = new SomeClassName();

$testClass->testMethod();

echo "End here";

```


#### Step 5: Execute the example

From the command line run `php -f index.php`

#### Step 6: Check the results

If everything went ok, then you should have a file called `report.html`, and the content should look something like this:

![Output report using ChartJS](https://github.com/juan-morales/footprint-php/blob/main/output_example.jpg "Output with ChartJS")


# Why this package?

This package was created in order to have a simple and small package, customizable, that could allow you to retrieve data while you execute your php scripts.

# Documentation (Work in progress)

Please refer to the wiki section of the project to learn more about the package design, usage, and how to extend it.

# Contributing to the project

Even though I am working on this package in order to improve it, feel free to open issues, send pull requests, creating new modules or enhancing the existing ones, etc. 

It is possible to create advance modules (like a live dashboard with websocket connection to update data on it, so we can have a real-time dashboard, etc.), but for the first release of this package I wanted to keep it simple and functional.

**All kind of help is welcome**

# Autor

Juan Carlos Morales <jcmargentina@gmail.com>

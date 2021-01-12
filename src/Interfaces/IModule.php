<?php declare(strict_types=1);

namespace Footprint\Interfaces;

use Footprint\Tracker;

interface IModule
{
    public function getId();

    public function onInit(Tracker &$tracker);

    public function onEnd(Tracker &$tracker);

    public function onLoad(Tracker &$tracker);

    public function onUnload(Tracker &$tracker);

    public function onLog(Tracker &$tracker);

    public function onLogBuild(Tracker &$tracker);

    public function getKeys() : array;
}
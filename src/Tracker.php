<?php declare(strict_types=1);

namespace Footprint;

use Footprint\Interfaces\IModule;

class Tracker 
{
    protected $dataTable = [];
    protected $modules = [];

    protected $logCallbacks = [];
    protected $logBuildCallbacks = [];
    protected $initCallbacks = [];
    protected $endCallbacks = [];
    
    protected $currentLogId = null;
    protected $currentLogData = [];

    /**
     * Public Events
     */
    public function onInit(callable $function) {
        $this->initCallbacks[] = $function;
        return $this;
    }

    public function onEnd(callable $function) {
        $this->endCallbacks[] = $function;
        return $this;
    }

    public function onLog(callable $function) {
        $this->logCallbacks[] = $function;
        return $this;
    }

    public function onLogBuild(callable $function) {
        $this->logBuildCallbacks[] = $function;
        return $this;
    }
    
    /**
     * Public methods
     */
    public function addLogData(string $key, $data) {
        $this->currentLogData[$key] = $data;
        return $this;
    }

    public function getLogDataByKey(string $key) {
        return $this->currentLogData[$key]?? null;
    }

    public function getLogData() {
        return $this->currentLogData;
    }

    public function getLogDataAsJSON() {
        return json_encode($this->currentLogData);
    }

    public function getLogId() {
        return $this->currentLogId;
    }

    public function init() {
        $this->executeModulesInit();

        foreach ($this->initCallbacks as $callback) {
            $callback($this);
        }

        return $this;
    }

    public function end() {
        $this->executeModulesEnd();

        $this->unloadAllModules();
        
        foreach ($this->endCallbacks as $callback) {
            $callback($this);
        }

        return $this;
    }

    public function loadModule(IModule &$module) {
        $module->onLoad($this);
        $this->modules[$module->getId()] = $module;
        return $this;
    }

    public function unloadModule(string $moduleId) {
        ($this->modules[$moduleId])->onUnload($this);
        unset($this->modules[$moduleId]);
        return $this;
    }

    public function unloadAllModules() {
        $this->executeModulesUnLoad();
        $this->modules = [];
        return $this;
    }

    public function log() {

        $this->currentLogId = date("Y-m-d H:i:s")."_".uniqid();
        $this->currentLogData = [];

        $this->executeModulesLogBuild();

        foreach ($this->logBuildCallbacks as $callback) {
            $callback($this->currentLogId, $this->currentLogData);
        }

        $this->executeModulesLog();

        foreach ($this->logCallbacks as $callback) {
            $callback($this->currentLogId, $this->currentLogData);
        }
        
        return $this;
    }

    /**
     * Data handling functions
     */

    public function deleteData(string $key) {
        unset($this->dataTable[$key]);
    }

    public function getData(string $key) {
        return $this->dataTable[$key]?? null;
    }

    public function setData(string $key, string $data) {
        $this->dataTable[$key] = $data;
        return $this;
    }

    /**
     * Private methods
     */
    private function executeModulesInit() {
        foreach ($this->modules as $id => $module) {
            $module->onInit($this);
        }
    }

    private function executeModulesEnd() {
        foreach ($this->modules as $id => $module) {
            $module->onEnd($this);
        }
    }

    private function executeModulesLog() {
        foreach ($this->modules as $id => $module) {
            $module->onLog($this);
        }
    }

    private function executeModulesLogBuild() {
        foreach ($this->modules as $id => $module) {
            $module->onLogBuild($this);
        }
    }

    private function executeModulesLoad() {
        foreach ($this->modules as $id => $module) {
            $module->onLoad($this);
        }
    }

    private function executeModulesUnLoad() {
        foreach ($this->modules as $id => $module) {
            $module->onUnLoad($this);
        }
    }
}

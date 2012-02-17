<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Core/Controllers/CoreController.php';
require_once 'Core/Utilities/Configuration.php';
require_once 'Core/Utilities/Loader.php';

/**
 * Description of Bootstrap
 *
 * @author smarkoski
 */
class Bootstrap {

    private $uri;
    private $controller;
    private $method;
    private $arguments;
    private $controllerFile;
    private $controllerObject;

    public function __construct() {
        xdebug_disable();
        $this->loadCoreConfig();

        $this->loadUserConfig();

        $this->createSPLAutoloaders();

        $this->uri = URIHelper::getURIArray();

        $this->prepareURI();

        $this->buildCMA(); //Controller, method and arguments

        $this->instantiateController();
    }

    

    public function run() {
        $this->executePreLaunchTasks();

        $this->executePrecontroller();

        $this->executeInvoke();

        $this->executePostcontroller();

        $this->executePostLaunchTasks();
    }

    private function createSPLAutoloaders() {
        spl_autoload_register(null, false);
        spl_autoload_extensions('.php');
        spl_autoload_register(array('Loader', 'loadException'));
        spl_autoload_register(array('Loader', 'loadUtility'));
        spl_autoload_register(array('Loader', 'loadController'));
        spl_autoload_register(array('Loader', 'loadTask'));
    }

    private function prepareURI() {
        //Shift off the basepath and discard it
        if (Configuration::read('basepath') != '') {
            $devnull = array_shift($this->uri);
        }

        //Deal with lack of mod-rewrite
        if (!Configuration::read('mod_rewrite_enabled')) {
            $devnull = array_shift($this->uri);
        }
    }

    private function buildCMA() {
        $this->controller = array_shift($this->uri);
        $this->method = array_shift($this->uri);
        $this->arguments = $this->uri;
    }

    private function instantiateController() {
        $this->controllerFile = Configuration::read('controller_path') . $this->controller . '.controller.php';

        if (file_exists($this->controllerFile)) {
            $this->controllerObject = new $this->controller();
        } else if (empty($this->controller)) {
            //Load the default controller
            $defaultController = Configuration::read('default_controller');
            $this->controllerObject = new $defaultController();
        } else {
            die('Missing controller: ' . $this->controllerFile);
        }
    }

    private function executePreLaunchTasks() {
        /*
         * Look for any pre-launch tasks
         */
        $preLaunch = scandir(Configuration::read('task_path'));
        foreach ($preLaunch as $pre) {
            if (preg_match('/prelaunch\.php$/', $pre) > 0) {
                $pre = preg_replace('/\..*$/', '', $pre);
                $task = new $pre();
                $task->execute();
            }
        }
    }

    private function executePrecontroller() {
        $validMethod = 'precontroller';
        if (method_exists($this->controllerObject, $this->method . '_precontroller')) {
            $validMethod = $this->method . '_precontroller';
        }
        $this->wrap_call_user_func_array($this->controllerObject, $validMethod, $this->arguments);
    }

    private function executeInvoke() {
        $validMethod = 'invoke';
        if (method_exists($this->controllerObject, $this->method . '_invoke')) {
            $validMethod = $this->method . '_invoke';
        }
        $this->wrap_call_user_func_array($this->controllerObject, $validMethod, $this->arguments);
    }

    private function executePostcontroller() {
        $validMethod = 'postcontroller';
        if (method_exists($this->controllerObject, $this->method . '_postcontroller')) {
            $validMethod = $this->method . '_postcontroller';
        }
        $this->wrap_call_user_func_array($this->controllerObject, $validMethod, $this->arguments);
    }

    private function executePostLaunchTasks() {
        //nothing here yet
    }

    private function wrap_call_user_func_array($c, $a, $p) {
        switch (count($p)) {
            case 0: $c->{$a}();
                break;
            case 1: $c->{$a}($p[0]);
                break;
            case 2: $c->{$a}($p[0], $p[1]);
                break;
            case 3: $c->{$a}($p[0], $p[1], $p[2]);
                break;
            case 4: $c->{$a}($p[0], $p[1], $p[2], $p[3]);
                break;
            case 5: $c->{$a}($p[0], $p[1], $p[2], $p[3], $p[4]);
                break;
            default: call_user_func_array(array($c, $a), $p);
                break;
        }
    }

    private function loadCoreConfig() {
        $filename = 'Core/Config/core_config.xml';
        $fp = fopen($filename, 'r');
        $xmlstr = fread($fp, filesize($filename));
        fclose($fp);
        $sxml = new SimpleXMLElement($xmlstr);
        $this->pathsConfig($sxml);
    }

    private function loadUserConfig() {
        $filename = 'App/Config/config.xml';
        $fp = fopen($filename, 'r');
        $xmlstr = fread($fp, filesize($filename));
        fclose($fp);
        $sxml = new SimpleXMLElement($xmlstr);
        $this->databaseConfig($sxml);
        $this->valuesConfig($sxml);
    }

    private function pathsConfig($sxml) {
        foreach ($sxml->paths->path as $path) {
            Configuration::write((string) $path['name'], (string)$path);
        }
    }

    private function databaseConfig($sxml) {
        foreach ($sxml->database as $db) {
            Configuration::write('db_host', (string)$db->host);
            Configuration::write('db_name', (string)$db->databasename);
            Configuration::write('db_username', (string)$db->username);
            Configuration::write('db_password', (string)$db->password);
        }
    }

    private function valuesConfig($sxml) {
        foreach ($sxml->values->value as $value) {
            Configuration::write((string)$value['name'], (string)$value);
        }
    }

}
?>

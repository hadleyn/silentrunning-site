<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loader
 *
 * @author smarkoski
 */
class Loader {

    public static function loadException($e) {
        $file = Configuration::read('exception_path') . $e . '.php';
        @include_once $file;
    }

    public static function loadUtility($e) {
        $file = Configuration::read('utility_path') . $e . '.php';
        @include_once $file;
    }

    public static function loadController($e) {
        $file = Configuration::read('controller_path') . $e . '.controller.php';
        @include_once $file;
    }

    public static function loadView($v, $viewData = NULL, $absolutePath = FALSE) {
        $file = Configuration::read('view_path') . $v . '.view.php';
        if (file_exists($file)) {
            extract($viewData);
            include $file;
        }
    }

    public static function loadTask($t) {
        $file = array();
        $file[] = Configuration::read('task_path') . $t . '.precontroller.php';
        $file[] = Configuration::read('task_path') . $t . '.postcontroller.php';
        $file[] = Configuration::read('task_path') . $t . '.prelaunch.php';
        $file[] = Configuration::read('task_path') . $t . '.postlaunch.php';
        $f = null;
        do {
            $f = array_shift($file);
        } while (count($file) > 0 && (@include_once $f) === FALSE);
    }

    public static function loadModule($m) {

        $file = 'Core/ConfigModules/' . $m . '.module.php';

        if ((@include_once $file) === FALSE) {
            $file = 'App/ConfigModules/' . $m . '.module.php';
            include_once $file;
        }
    }

}

?>

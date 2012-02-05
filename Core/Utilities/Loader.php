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
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public static function loadUtility($e) {
        $file = Configuration::read('utility_path') . $e . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public static function loadController($e) {
        $file = Configuration::read('controller_path') . $e . '.controller.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public static function loadView($v, $viewData=NULL, $absolutePath=FALSE) {
        $file = Configuration::read('view_path') . $v . '.view.php';
        if (file_exists($file)) {
            extract($viewData);
            include $file;
        }
    }

    public static function loadTask($t) {
        $file = Configuration::read('task_path') . $t . '.precontroller.php';
        if (file_exists($file)) {
            require_once $file;
        }
        $file = Configuration::read('task_path') . $t . '.postcontroller.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

}
?>

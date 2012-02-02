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
        $file = 'Core/Exceptions/' . $e . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public static function loadUtility($e) {
        $file = 'Core/Utilities/' . $e . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public static function loadController($e) {
        $file = 'Core/Controllers/' . $e . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            $file = 'App/Controllers/' . $e . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

}
?>

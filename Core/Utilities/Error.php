<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Error
 *
 * @author smarkoski
 */
class Error {

    private $sessionHelper;

    public function __construct() {
        $this->sessionHelper = new Session();
    }

    public function showErrors($echo = TRUE) {
        $msg = '';
        try {
            $errors = $this->sessionHelper->read('sr_errors');
        } catch (SessionDataIOException $e) {
            $errors = array();
        }
        while ($e = array_shift($errors)) {
            $msg .= '<div class="' . Configuration::read('error_div_class') . '">' . $e . '</div>';
        }
        $this->sessionHelper->destroy('sr_errors');
        if (!$echo) {
            return $msg;
        }
        echo $msg;
    }

    public function pushError($error) {
        try {
            $errors = $this->sessionHelper->read('sr_errors');
        } catch (SessionDataIOException $e) {
            $errors = array();
        }
        if (is_array($error)) {
            $errors = array_merge($errors, $error);
        } else {
            $errors[] = $error;
        }
        $this->sessionHelper->write('sr_errors', $errors, TRUE);
    }

    public function hasErrors() {
        try {
            $errors = $this->sessionHelper->read('sr_errors');
        } catch (SessionDataIOException $e) {
            $errors = array();
        }
        return count($errors) > 0;
        
    }

}

?>

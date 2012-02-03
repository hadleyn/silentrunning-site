<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Core/Controllers/ICoreController.php';
/**
 * Description of CoreController
 *
 * @author smarkoski
 */
abstract class CoreController implements ICoreController {

    protected $viewData;
    protected $errorHelper;
    protected $infoHelper;
    protected $viewPrefix;

    public function __construct() {
        $this->viewData = array();
        $this->viewPrefix = '';
    }

    public function precontroller() {

    }

    public function invoke() {

    }

    public function postcontroller() {

    }

    protected function loadView($view, $absolutePath=FALSE) {
        $file = $this->viewPrefix . $view;
        if ($absolutePath) {
            $file = $view;
        }

        if (file_exists($file)) {
            extract($this->viewData);
            include $file;
        }
    }

}
?>

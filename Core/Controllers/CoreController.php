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
    /**
     * A handy error helper class.
     * @var Error 
     */
    protected $errorHelper;
    protected $infoHelper;
    protected $viewPrefix;

    public function __construct() {
        $this->viewData = array();
        $this->viewPrefix = '';
        $this->errorHelper = new Error();
    }
    
    public function preajax() {
        // this doesn't do anything by default
    }
    
    public function precontroller() {
        $preController = scandir(Configuration::read('task_path'));
        foreach ($preController as $pre) {
            if (preg_match('/precontroller\.php$/', $pre) > 0) {
                $pre = preg_replace('/\..*$/', '', $pre);
                $task = new $pre();
                $task->execute();
            }
        }
    }

    public function invoke() {

    }

    public function postcontroller() {
        $postController = scandir(Configuration::read('task_path'));
        foreach ($postController as $post) {
            if (preg_match('/postcontroller.php$/', $post) > 0) {
                $post = preg_replace('/\..*$/', '', $post);
                $task = new $post();
                $task->execute();
            }
        }
    }

    public function bufferedControllerCall($method, $arguments=array()) {
        $result = '';
        if (method_exists($this, $method)) {
            ob_start();
            call_user_func_array(array($this, $method), $arguments);
            $result = ob_get_contents();
        }
        ob_clean();
        return $result;
    }

    protected function loadView($view) {
        $file = $this->viewPrefix . $view;
        $this->viewData['errorHelper'] = $this->errorHelper;
        Loader::loadView($file, $this->viewData);
    }
    
    protected function redirect($location){
        header('Location: '.$location);
        exit;
    }

}
?>

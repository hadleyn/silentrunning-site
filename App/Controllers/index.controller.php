<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author smarkoski
 */
class index extends CoreController {

    protected function header(){
        $this->loadView('includes/header');
    }

    protected function footer(){
        $this->loadView('includes/footer');
    }

    protected function sidebar(){
        $this->loadView('includes/sidebar');
    }

    public function precontroller(){
        $headHelper = HeadHelper::getInstance();
        $headHelper->addScript('recaptcha');
        $headHelper->addScript('login');
        parent::precontroller();
    }

    public function invoke(){
        require_once('App/Utilities/recaptchalib.php');
        $this->loadView('index');
    }


}
?>

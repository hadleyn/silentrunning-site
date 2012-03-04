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
        $cookie = new Cookie();
        $cryptor = new Cryptor();
        try {
            $usercookie = $cookie->read('sr_user');
            if (!$cryptor->verifySecureString($usercookie)){
                $this->viewData['userLoggedIn'] = TRUE;
            } else {
                $this->viewData['userLoggedIn'] = FALSE;
            }
        } catch (CookieDataIOException $e) {
            $this->viewData['userLoggedIn'] = FALSE;
        }
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

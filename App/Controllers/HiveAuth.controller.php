<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HiveAuthController
 *
 * @author smarkoski
 */
abstract class HiveAuth extends CoreController {

    public function __construct() {
        parent::__construct();
        $this->user = new user();
    }

    public function preajax() {

        try {
            $this->getUserFromCookie();
        } catch (CookieDataIOException $e) {
            $this->messageHelper->pushError('You must be logged in to view the hive.');
        }
    }

    public function precontroller() {
        $session = new Session();
        try {
            $this->getUserFromCookie();
            try {
                $redirect = $session->read('loginRedirect');
                $session->destroy('loginRedirect');
                $this->redirect($redirect);
            } catch (SessionDataIOException $e) {
                parent::precontroller();
            }
        } catch (CookieDataIOException $e) {
            
            $this->messageHelper->pushError('You must be logged in to view the hive.');
            $session->write('loginRedirect', URIHelper::getRequestURI(), TRUE);
            $this->redirect(Configuration::read('basepath'));
        }
    }

    private function getUserFromCookie() {
        $cookie = new Cookie();
        $temp = $cookie->read(Configuration::read('auth_cookie_name'));
        $cryptor = new Cryptor();
        if (!$cryptor->verifySecureString($temp, 'sha256')) {
            throw new CookieDataIOException();
        }
        list($userid, $handle) = $cryptor->getSecureData($temp);
        $this->user->getUserByHandle($handle);
    }

}

?>

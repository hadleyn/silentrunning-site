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
            $this->errorHelper->pushError('You must be logged in to view the hive.');
        }
    }

    public function precontroller() {
        try {
            $this->getUserFromCookie();
            parent::precontroller();
        } catch (CookieDataIOException $e) {
            $this->errorHelper->pushError('You must be logged in to view the hive.');
            $this->redirect(Configuration::read('basepath'));
        }
    }

    private function getUserFromCookie() {
        $cookie = new Cookie();
        $temp = $cookie->read('sr_user');
        $cryptor = new Cryptor();
        if (!$cryptor->verifySecureString($temp, 'sha256')) {
            throw new CookieDataIOException();
        }
        list($userid, $handle) = $cryptor->getSecureData($temp);
        $this->user->getUserByHandle($handle);
    }

}

?>

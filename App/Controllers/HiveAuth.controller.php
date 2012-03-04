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

    public function precontroller() {
        $cookie = new Cookie();
        try {
            $cookie->read('sr_user');
            parent::precontroller();
        } catch (CookieDataIOException $e) {
            $this->errorHelper->pushError('You must be logged in to view the hive.');
            $this->redirect(Configuration::read('basepath'));
        }
    }

}

?>

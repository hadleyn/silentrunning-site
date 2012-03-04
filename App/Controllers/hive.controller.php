<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hive
 *
 * @author smarkoski
 */
class hive extends HiveAuth {

//    protected $user;

    public function __construct() {
        parent::__construct();
        $this->user = new user();
    }

    public function invoke() {
        $this->loadView('hive');
    }

    public function logout_precontroller() {
        $cookie = new Cookie();
        try {
            $cookie->clear('sr_user');
        } catch (CookieDataIOException $e) {
            //nothing to do here
        }
        $this->redirect(Configuration::read('basepath'));
    }
    
    public function login_precontroller() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('handle'), 'handle'));
        $validator->addRule(new authenticateuser(Input::post('handle'), Input::post('password')));
        $this->errorHelper->pushError($validator->run());
        if ($this->errorHelper->hasErrors()){
            $this->redirect(Configuration::read('basepath'));
        } else {
            $this->precontroller();
        }
    }

    public function register_precontroller() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new alphanumeric(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new uniqueusername(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new required(Input::post('registerPassword'), 'password'));
        $validator->addRule(new matches(Input::post('registerPassword'), Input::post('registerPasswordConf'), 'password', 'password confirm'));
        $validator->addRule(new recaptcha(Input::post('recaptcha_challenge_field'), Input::post('recaptcha_response_field')));
        $this->errorHelper->pushError($validator->run());
        if ($this->errorHelper->hasErrors()) {
            $this->redirect(Configuration::read('basepath'));
        } else {
            $user = new User();
            $user->handle = Input::post('registerHandle');
            $user->password = Input::post('registerPassword');
            $user->insertNewUser();
            $user->authenticateHandlePassword(Input::post('registerHandle'), Input::post('registerPassword'));
        }
        $this->precontroller();
    }

    public function checkhandle_ajax() {
        $this->user->getUserByHandle(Input::post('handle'));
        $result = array();
        $result['handleOk'] = TRUE;
        if ($this->user->userid > 0) {
            $result['handleOk'] = FALSE;
        }
        echo json_encode($result);
    }

}

?>

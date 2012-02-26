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

    public function register_precontroller() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new required(Input::post('registerPassword'), 'password'));
        $validator->addRule(new matches(Input::post('registerPassword'), Input::post('registerPasswordConf'), 'password', 'password confirm'));
        $this->errorHelper->pushError($validator->run());
        if ($this->errorHelper->hasErrors()){
            $this->redirect(Configuration::read('basepath'));
        } else {
            $user = new User();
            $user->handle = Input::post('registerHandle');
            $user->password = Input::post('registerPassword');
            $user->insertNewUser();
            
            $this->redirect(Configuration::read('basepath').'/hive');
        }
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

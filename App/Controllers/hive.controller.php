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

    public function checkhandle_ajax() {
        $this->user->getUserByHandle(Input::post('handle'));
        $result = array();
        $result['handleOk'] = TRUE;
        if ($this->user->userid > 0){
            $result['handleOk'] = FALSE;
        }
        echo json_encode($result);
    }

}

?>

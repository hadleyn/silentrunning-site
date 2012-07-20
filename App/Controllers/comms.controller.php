<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of comms
 *
 * @author smarkoski
 */
class comms extends HiveAuth {
    
    public function __construct() {
        parent::__construct();
        $headHelper = HeadHelper::getInstance();
        $headHelper->addScript('alerts');
    }
    
    public function invoke() {
        $alert = new alert();
        $this->viewData['alerts'] = $alert->getAllAlertsByUserID(user::getCurrentUserID()); 
        $this->loadView('alerts');
    }
    
    public function viewAlert_ajax() {
        $alert = new alert();
        $alert->markAlertAsRead(Input::post('alertID'));
    }
    
}

?>

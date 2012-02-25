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

    public function invoke() {
        $this->loadView('hive');
    }
    
    public function ajaxtest_ajax(){
        echo json_encode('win');
    }

}

?>

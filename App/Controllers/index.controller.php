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
class index extends CoreController implements ICoreController {

    protected function header(){
        $this->loadView('includes/header');
    }

    protected function footer(){
        $this->loadView('includes/footer');
    }

    protected function sidebar(){
        $this->loadView('includes/sidebar');
    }


    public function invoke(){
        $db = DB::instance();
        $db->query('INSERT INTO test (val) VALUES (?)', array('s'), array('hello'));
        $this->loadView('index');
    }


}
?>

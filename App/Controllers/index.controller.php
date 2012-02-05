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


    public function invoke(){
        $this->loadView('index');
    }


}
?>

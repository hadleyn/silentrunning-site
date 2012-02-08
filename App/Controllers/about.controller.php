<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of about
 *
 * @author smarkoski
 */
class about extends CoreController implements ICoreController {

    public function invoke(){
        $this->loadView('about');
    }

}
?>

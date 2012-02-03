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


    public function invoke(){
        echo '<h2>Welcome to the silentrunning framework!</h2>';
        print_r(func_get_args());
    }

    public function method_invoke(){
        echo 'an overriden method!';
    }


}
?>

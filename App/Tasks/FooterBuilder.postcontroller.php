<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FooterBuilder
 *
 * @author smarkoski
 */
class FooterBuilder implements Task {

    public function execute() {
        $indexController = new index();
        echo $indexController->bufferedControllerCall('footer');
    }

}
?>

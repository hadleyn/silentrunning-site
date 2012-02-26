<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author smarkoski
 */
interface Rule {
    
    public function run();
    public function getError();
}

?>

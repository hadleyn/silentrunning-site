<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author smarkoski
 */
interface ialertsubscriber {

    /**
     * Process the given alert
     * 
     * @param Alert $alert The alert object to process 
     */
    public function processAlert($alert);
    
}

?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newcommentalert
 *
 * @author smarkoski
 */
class newcommentalert extends alert {

    
    
    public function __construct() {
        $this->type = Configuration::read('new_comment');
    }
    
    /**
     *
     * @param content $content 
     */
    public function sendAlert($content) {
        $subscribers = $this->getAlertSubscribers();
        $parentContent = new Content();
        $parentContent->getContent($content->parentid);
        while ($parentContent->parentid > 0) {
            
        }
    }

}

?>

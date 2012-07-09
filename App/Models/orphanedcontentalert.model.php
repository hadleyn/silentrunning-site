<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of orphanedcontentalert
 *
 * @author smarkoski
 */
class orphanedcontentalert extends alert {

    public function __construct() {
        $this->type = Configuration::read('orphaned_content');
    }

    /**
     *
     * @param content $content 
     */
    public function sendAlert($content) {
        $qMessage = new QMessage();
        $qMessage->msg_obj = 'orphanedcommentalert';
        $qMessage->msg_method = 'insertAlert';
        $qMessage->msg_args = array($content->ownerid, $this->type, 'One of your comments has been orphaned!', '');
        $qMessage->queueMessage();
    }

}

?>

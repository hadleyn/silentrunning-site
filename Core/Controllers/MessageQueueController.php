<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Core/Models/QMessage.php';
/**
 * Description of MessageQueueController
 *
 * @author smarkoski
 */
class MessageQueue extends CoreController {
    
    
    public function invoke() {
        $qMessage = new QMessage();
        $qMessage->getNextMessage();
        
        if (method_exists($qMessage->msg_obj, $qMessage->msg_method)) {
            $obj = new $qMessage->msg_obj();
            call_user_func_array(array($obj, $qMessage->msg_method), $qMessage->msg_args);
        }
        
        //We don't care about errors here, delete the message
        $qMessage->deleteMessage();
    }
    
    
}

?>

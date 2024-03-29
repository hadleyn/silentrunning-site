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
        $subscriber = new user();
        $subscriber->getUserByHandle(user::getCurrentUserID());
        $alertPreferences = $subscriber->getAlertPreferences();
        if (isset($alertPreferences[$this->type])) {
            $qMessage = new QMessage();
            $qMessage->msg_obj = 'orphanedcontentalert';
            $qMessage->msg_method = 'insertAlert';
            $qMessage->msg_args = array($content->ownerid, $this->type, 'One of your comments has been orphaned!', '/orphancontent#cid' . $content->contentid);
            $qMessage->queueMessage();
        }
    }
    
    public function insertAlert($recipient, $type, $message, $url) {
        $hash = md5($recipient . $type . $message . $url);
        if (!$this->alertExists($hash)) {
            $db = DB::instance();
            
            $query = 'INSERT INTO alerts (recipient, type, message, url, `read`, timestamp) VALUES (?, ?, ?, ?, 0, NOW())';
            $db->query($query, 'i,s,s,s,s', array($recipient, $type, $message, $url));
        }
    }

}

?>

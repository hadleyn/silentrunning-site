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
        $commenter = new user();
        $commenter->getUserByHandle($content->ownerid);
        $subscribers = $this->getAlertSubscribers($content);
        foreach ($subscribers as $s) {
            $qMessage = new QMessage();
            $qMessage->msg_obj = 'newcommentalert';
            $qMessage->msg_method = 'insertAlert';
            $qMessage->msg_args = array($s->userid, $this->type, $commenter->handle . ' has posted a comment on your content!', '');
            $qMessage->queueMessage();
        }
    }

    /**
     * The content that comes into this method will be the new content that was just
     * added.
     * 
     * @param content $content
     * @return user 
     */
    public function getAlertSubscribers($content) {
        $subscribers = array();
        $parentContent = new Content();
        $parentContent->getContent($content->parentid);

        //Do the first one
        $subscriber = new user();
        $subscriber->getUserByHandle($parentContent->ownerid);
        $prefs = $subscriber->getAlertPreferences();
        if (isset($prefs[$this->type])) {
            $subscribers[] = $subscriber;
        }

        //Now go up the chain adding any others
        while ($parentContent->parentid > 0) {
            $currentContentID = $parentContent->parentid;
            $subscriber = new user();
            $subscriber->getUserByHandle($parentContent->ownerid);
            $prefs = $subscriber->getAlertPreferences();
            if (isset($prefs[$this->type])) {
                $subscribers[] = $subscriber;
            }
            $parentContent = new content();
            $parentContent->getContent($currentContentID);
        }
        return $subscribers;
    }

}

?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */





/**
 * Description of alert
 *
 * @author smarkoski
 */
class alert extends CoreModel implements Event {
    
    protected $alertid;
    protected $recipient;
    protected $type;
    protected $message;
    protected $url;
    protected $timestamp;
    
    public function getAlertSubscribers()
    {
        
    }
    
    protected function insertAlert()
    {
        $db = DB::instance();
        $query = 'INSERT INTO alerts (recipient, type, message, url, timestamp) VALUES (?, ?, ?, ? NOW())';
        $db->query($query, 'isss', array($this->recipient, $this->type, $this->message, $this->url));
    }
    
}

?>

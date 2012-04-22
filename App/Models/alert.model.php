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
abstract class alert extends CoreModel implements Event {
    
    protected $alertid;
    protected $recipient;
    protected $type;
    protected $message;
    protected $url;
    protected $timestamp;
    
    public function insertAlert($recipient, $type, $message, $url)
    {
        $db = DB::instance();
        $query = 'INSERT INTO alerts (recipient, type, message, url, timestamp) VALUES (?, ?, ?, ?, NOW())';
        $db->query($query, 'i,s,s,s', array($recipient, $type, $message, $url));
    }
    
}

?>

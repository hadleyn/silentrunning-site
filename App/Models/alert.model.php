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
    protected $read;
    protected $timestamp;
    
    public function insertAlert($recipient, $type, $message, $url)
    {
        $db = DB::instance();
        $query = 'INSERT INTO alerts (recipient, type, message, url, `read`, timestamp) VALUES (?, ?, ?, ?, 0, NOW())';
        $db->query($query, 'i,s,s,s', array($recipient, $type, $message, $url));
    }
    
    public function getAllAlertsByUserID($id) {
        $db = DB::instance();
        $query = 'SELECT * FROM alerts WHERE recipient=? ORDER BY timestamp DESC';
        $db->query($query, 'i', array($id));
        $alerts = array();
        while ($result = $db->fetchResult()) {
            $alert = new alert();
            $alert->populate($result);
            $alerts[] = $alert;
        }
        return $alerts;
    }
    
}

?>

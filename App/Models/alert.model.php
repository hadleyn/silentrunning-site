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
    protected $hash;

    public function insertAlert($recipient, $type, $message, $url, $commenterid) {
        $hash = md5($recipient . $type . $message . $url . $commenterid);
        if (!$this->alertExists($hash)) {
            $db = DB::instance();
            
            $query = 'INSERT INTO alerts (recipient, type, message, url, `read`, timestamp, hash) VALUES (?, ?, ?, ?, 0, NOW(), ?)';
            $db->query($query, 'i,s,s,s,s', array($recipient, $type, $message, $url, $hash));
        }
    }

    public function getAllAlertsByUserID($id) {
        $db = DB::instance();
        $query = 'SELECT * FROM alerts WHERE recipient=? AND `read`=0 ORDER BY timestamp DESC';
        $db->query($query, 'i', array($id));
        $alerts = array();
        while ($result = $db->fetchResult()) {
            $alert = new alert();
            $alert->populate($result);
            $alerts[] = $alert;
        }
        return $alerts;
    }

    public function markAlertAsRead($alertid) {
        $db = DB::instance();
        $query = 'UPDATE alerts SET `read`=1 WHERE alertid=?';
        $db->query($query, 'i', array($alertid));
    }

    protected function alertExists($hash) {
        $db = DB::instance();
        $query = 'SELECT COUNT(*) AS c FROM alerts WHERE hash=?';
        $db->query($query, 's', array($hash));
        $result = $db->fetchResult();
        if ($result['c'] > 0) {
            return TRUE;
        }
        return FALSE;
    }

}

?>

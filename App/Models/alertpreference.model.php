<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of alertpreference
 *
 * @author smarkoski
 */
class alertpreference extends CoreModel {

    protected $userid;
    protected $alert_type;
    protected $preference;

    public function updateAlertPreference() {
        $db = DB::instance();
        $query = 'SELECT COUNT(*) AS c FROM alert_preferences WHERE userid=? AND alert_type=?';
        $db->query($query, 'is', array($this->userid, $this->alert_type));
        $count = $db->fetchResult();
        if ($count['c'] > 0) {
            $query = 'UPDATE alert_preferences SET preference=? WHERE userid=? AND alert_type=?';
            $db->query($query, 'iis', array($this->preference, $this->userid, $this->alert_type));
        } else {
            $query = 'INSERT INTO alert_preferences (userid, alert_type, preference) VALUES (?, ?, ?)';
            $db->query($query, 'isi', array($this->userid, $this->alert_type, $this->preference));
        }
    }

}

?>

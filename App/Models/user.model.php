<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author smarkoski
 */
class user extends CoreModel implements ialertsubscriber {

    protected $userid;
    protected $handle;
    protected $password;
    protected $email;

    public function __construct() {
        $this->userid = -1;
    }

    public static function getCurrentUserID() {
        $cookie = new Cookie();
        $cryptor = new Cryptor();
        $temp = $cookie->read(Configuration::read('auth_cookie_name'));
        $userid = -1;
        if ($cryptor->verifySecureString($temp, 'sha256')) { //I don't think this is necessary, but why chance it
            list($userid, $handle) = $cryptor->getSecureData($temp);
        }
        return $userid;
    }

    /**
     * Populate $this with the user with the handle provided.
     * 
     * @param mixed $handle The handle of the user to search for. Can also be a user ID.
     */
    public function getUserByHandle($handle) {
        $db = DB::instance();
        $query = 'SELECT * FROM users WHERE handle=? OR userid=? LIMIT 1';
        $db->query($query, array('si'), array($handle, $handle));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        if (isset($result)) {
            $this->populate($result);
        }
    }

    /**
     * Insert user into the database using $this. 
     */
    public function insertNewUser() {
        $db = DB::instance();
        $hashedPassword = $this->hashPassword();
        $query = 'INSERT INTO users (handle, password) VALUES (?, ?)';
        $db->query($query, array('s', 's'), array($this->handle, $hashedPassword));
        $this->userid = $db->insert_id;
        $this->createMysqlUser();
    }

    public function authenticateHandlePassword($handle, $password) {
        $db = DB::instance();
        $this->password = $password;
        $hashedPassword = $this->hashPassword();
        $query = 'SELECT *, COUNT(*) AS c FROM users WHERE handle=? AND password=?';
        $db->query($query, array('s', 's'), array($handle, $hashedPassword));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        if ($result['c'] > 0) {
            $this->writeCookie($result['userid'], $handle);
            return TRUE;
        }
        return FALSE;
    }

    public function authenticateCookie() {
        $cookie = new Cookie();
        $cryptor = new Cryptor();
        try {
            if ($cryptor->verifySecureString($cookie->read(Configuration::read('auth_cookie_name')), 'sha256')) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (CookieDataIOException $e) {
            return FALSE;
        }
    }

    private function writeCookie($userid, $handle) {
        $cookie = new Cookie();
        $cryptor = new Cryptor();
        $cookiePackage = $cryptor->createSecureString(array($userid, $handle), 'sha256');
        $cookie->write(Configuration::read('auth_cookie_name'), $cookiePackage, 3600, '/', TRUE);
    }

    private function hashPassword() {
        $db = DB::instance();
        $db->query('SELECT value FROM secret WHERE name="secretkey1"');
        $result = $db->fetchResult();
        $db->cleanupConnection();
        $dbSecureKey = $result['value'];
        return hash_hmac('sha512', $this->password . Configuration::read('random_salt') . $dbSecureKey, Configuration::read('random_salt') . $dbSecureKey);
    }

    private function createMysqlUser() {
        $db = DB::instance();
        $mysqli = $db->getMysqli();
        $mysqli->query("CREATE USER '$this->handle'@'%' IDENTIFIED BY '$this->password'");
        $mysqli->query("
        CREATE DEFINER=`sr`@`%` PROCEDURE `silentrunning`.`" . $this->handle . "_user_select` ()
        BEGIN
            select * from users where userid=" . $this->userid . ";
        END");
        $mysqli->query("GRANT EXECUTE ON PROCEDURE `silentrunning`.`" . $this->handle . "_user_select` TO '$this->handle'@'%'");
    }

    
    public function getAlertPreferences() {
        $db = DB::instance();
        $query = 'SELECT * FROM alert_preferences WHERE userid = ? AND preference = 1';
        $db->query($query, 'i', array($this->userid));
        $preferences = array();
        while ($resultRow = $db->fetchResult()) {
            $alertPreference = new alertpreference();
            $alertPreference->populate($resultRow);
            $preferences[$alertPreference->alert_type] = $alertPreference;
        }
        return $preferences;
    }
            
            
    public function getAlerts() {
        $alert = new alert();
        $alerts = $alert->getAllAlertsByUserID($this->userid);
        return $alerts;
    }
    
    /**
     * @param Alert $alert
     */
    public function processAlert($alert) {
        
    }

}

?>

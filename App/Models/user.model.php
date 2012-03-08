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
class user extends CoreModel {

    protected $userid;
    protected $handle;
    protected $password;
    protected $email;

    public function __construct() {
        $this->userid = -1;
    }

    /**
     * Populate $this with the user with the handle provided.
     * 
     * @param string $handle The handle of the user to search for.
     */
    public function getUserByHandle($handle) {
        $db = DB::instance();
        $query = 'SELECT * FROM users WHERE handle=?';
        $db->query($query, array('s'), array($handle));
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
        $this->createMysqlUser();
    }

    public function authenticateHandlePassword($handle, $password) {
        $db = DB::instance();
        $this->password = $password;
        $hashedPassword = $this->hashPassword();
        $query = 'SELECT COUNT(*) AS c FROM users WHERE handle=? AND password=?';
        $db->query($query, array('s', 's'), array($handle, $hashedPassword));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        if ($result['c'] > 0) {
            $this->writeCookie($handle);
            return TRUE;
        }
        return FALSE;
    }

    public function authenticateCookie() {
        $cookie = new Cookie();
        $cryptor = new Cryptor();
        try {
            if ($cryptor->verifySecureString($cookie->read('sr_user'), 'sha256')) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (CookieDataIOException $e) {
            return FALSE;
        }
    }

    private function writeCookie($handle) {
        $cookie = new Cookie();
        $cryptor = new Cryptor();
        $cookiePackage = $cryptor->createSecureString($handle, 'sha256');
        $cookie->write('sr_user', $cookiePackage, 3600, '/', TRUE);
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
        CREATE DEFINER=`sr`@`%` PROCEDURE `silentrunning`.`".$this->handle."_user_select` ()
        BEGIN
            select * from users where userid=".$this->userid.";
        END");
        $mysqli->query("GRANT EXECUTE ON PROCEDURE `silentrunning`.`" . $this->handle . "_user_select` TO '$this->handle'@'%'");
    }

}

?>

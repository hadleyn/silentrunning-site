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
        $this->populate($result);
    }

    /**
     * Insert user into the database using $this. 
     */
    public function insertNewUser() {
        $db = DB::instance();
        $hashedPassword = $this->hashPassword();
        $query = 'INSERT INTO users (handle, password) VALUES (?, ?)';
        $db->query($query, array('s', 's'), array($this->handle, $hashedPassword));
    }

    public function authenticateHandlePassword($handle, $password) {
        $db = DB::instance();
        $this->password = $password;
        $hashedPassword = $this->hashPassword();
        $query = 'SELECT COUNT(*) AS c FROM users WHERE handle=? AND password=?';
        $db->query($query, array('s', 's'), array($handle, $hashedPassword));
        $result = $db->fetchResult();
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
        $cookie->write('sr_user', $cookiePackage);
    }

    private function hashPassword() {
        return hash_hmac('sha256', $this->password . Configuration::read('random_salt'), Configuration::read('random_salt'));
    }

}

?>

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
    
    public function __construct(){
        $this->userid = -1;
    }
    
    /**
     * Populate $this with the user with the handle provided.
     * 
     * @param string $handle The handle of the user to search for.
     */
    public function getUserByHandle($handle){
        $db = DB::instance();
        $query = 'SELECT * FROM users WHERE handle=?';
        $result = $db->query($query, array('s'), array($handle));
        $this->populate($result);
    }
    
    /**
     * Insert user into the database using $this. 
     */
    public function insertNewUser(){
        $db = DB::instance();
        $hashedPassword = $this->hashPassword();
        $query = 'INSERT INTO users (handle, password) VALUES (?, ?)';
        $db->query($query, array('s','s'), array($this->handle, $hashedPassword));
    }
    
    public function authenticateUsernamePassword($username, $password){
        
    }
    
    private function hashPassword(){
        return hash_hmac('sha256', $this->password.Configuration::read('random_salt'), Configuration::read('random_salt'));
    }
    
    
}

?>

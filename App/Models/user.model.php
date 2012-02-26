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
    
}

?>

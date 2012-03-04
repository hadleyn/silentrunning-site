<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of authenticateuser
 *
 * @author smarkoski
 */
class authenticateuser extends Rule {

    private $handle;
    private $password;
    
    public function __construct($handle, $password){
        $this->handle = $handle;
        $this->password = $password;
    }
    
    public function getError() {
        return 'Handle/password combination is incorrect';
    }

    public function run() {
        $user = new User();
        if ($user->authenticateHandlePassword($this->handle, $this->password)){
            return TRUE;
        }
        return FALSE;
    }

}

?>

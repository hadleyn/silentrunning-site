<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of uniqueusername
 *
 * @author smarkoski
 */
class uniqueusername extends Rule {

    public function getError() {
        return 'The handle '.$this->value.' is already in use';
    }

    public function run() {
        $user = new User();
        $user->getUserByHandle($this->value);
        if ($user->userid > 0) {
            return FALSE;
        }
        return TRUE;
    }

}

?>

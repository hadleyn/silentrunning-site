<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of maxlength
 *
 * @author smarkoski
 */
class maxlength implements Rule {

    private $fieldname;
    private $value;
    private $maxlength;
    
    public function __construct($value, $maxlength, $fieldname){
        $this->value = $value;
        $this->maxlength = $maxlength;
        $this->fieldname = $fieldname;
    }
    
    public function getError() {
        return 'The value for '.$this->fieldname.' must be less than '.$this->maxlength .' characters in length';
    }

    public function run() {
        if (strlen($this->value) > $this->maxlength) {
            return FALSE;
        }
        return TRUE;
    }

}

?>

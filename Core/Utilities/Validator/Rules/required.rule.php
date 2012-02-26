<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of required
 *
 * @author smarkoski
 */
class required implements Rule {

    private $fieldName;
    private $value;

    public function __construct($value, $fieldName){
        $this->value = $value;
        $this->fieldName = $fieldName;
    }
    
    public function getError() {
        return 'The '.$this->fieldName.' is required';
    }

    public function run() {
        if (empty($this->value)){
            return FALSE;
        }
        return TRUE;
    }

}

?>

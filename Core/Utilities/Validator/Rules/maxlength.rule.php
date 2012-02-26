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
    
    public function __construct($value, $maxlength){
        $this->value = $value;
        $this->maxlength = $maxlength;
    }
    
    public function getError() {
        return '';
    }

    public function run() {
        
    }

}

?>

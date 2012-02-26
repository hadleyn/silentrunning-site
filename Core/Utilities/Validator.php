<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validator
 *
 * @author smarkoski
 */
class Validator {
    
    private $rules;
    private $errors;
    
    public function __construct(){
        $this->rules = array();
        $this->errors = array();
        $this->loadCoreRules();
        $this->loadUserRules();
    }
    
    private function loadCoreRules(){
        $coreRules = scandir('Validator/Rules');
        $this->addRules($coreRules);
    }
    
    private function loadUserRules(){
        $userRules = scandir(Configuration::read('user_validation_rules_path'));
        $this->addRules($userRules);
    }
    
    private function addRules($files){
        foreach ($files as $r) {
            if (preg_match('/rule\.php$/', $r) > 0) {
                $r = preg_replace('/\..*$/', '', $r);
                $rule = new $r();
                $this->rules[] = $rule;
            }
        }
    }
    
    public function run(){
        foreach ($this->rules as $rule){
            if (!$rule->run()){
                $this->errors[] = $rule->getError();
            }
        }
        return $this->errors;
    }
    
}

?>

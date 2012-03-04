<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of recaptcha
 *
 * @author smarkoski
 */
class recaptcha extends Rule {
    
    private $challengeField;
    private $responseField;
    
    public function __construct($challengeField, $responseField){
        $this->challengeField = $challengeField;
        $this->responseField = $responseField;
    }
    
    public function getError() {
        return 'The recaptcha field was not correct';
    }
    public function run() {
        require_once('App/Utilities/recaptchalib.php');
        $resp = recaptcha_check_answer (Configuration::read('captcha_priv_key'),
                                $_SERVER["REMOTE_ADDR"],
                                $this->challengeField,
                                $this->responseField);
        if (!$resp->is_valid) {
            return FALSE;            
        }
        return TRUE;
    }
}

?>

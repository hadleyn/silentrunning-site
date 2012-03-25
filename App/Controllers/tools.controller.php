<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tools
 *
 * @author smarkoski
 */
class tools extends HiveAuth {

    public function __construct() {
        parent::__construct();
        $headHelper = HeadHelper::getInstance();
        $headHelper->addScript('tools');
    }

    public function invoke() {
        $this->loadView('tools');
    }

    public function createColonyLink_ajax() {
        $cryptor = new Cryptor();
        $colonyLink = new colonylink();
        $expires = strtotime(Input::post('expires'));
        if ($expires <= time()) {
            $expires = null;
        }
        if (empty($expires)) {
            $expires = strtotime('+2 days');
        }
        $linkKey = $cryptor->createSecureString(array(user::getCurrentUserID()), 'sha512');
        $colonyLink->createColonyLink($linkKey, $expires);
        $result['link'] = 'http://'.HOST . BASEPATH . '/colony/addlink/' . $linkKey;
        echo json_encode($result);
    }

}

?>

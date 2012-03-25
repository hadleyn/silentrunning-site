<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of colony
 *
 * @author smarkoski
 */
class colony extends HiveAuth {

    public function __construct() {
        parent::__construct();
    }

    public function addlink_precontroller($hash) {
        parent::precontroller();
        $cryptor = new Cryptor();
        $colonyLink = new colonylink();
        if ($cryptor->verifySecureString($hash, 'sha512')) {
            list($relateTo) = $cryptor->getSecureData($hash);
            $colonyLink->insertUserRelation($relateTo);
            $this->messageHelper->pushMessage('Colony link successfully established!');
            $this->redirect(BASEPATH.'/hive');
        }
        
    }

}

?>

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
        $usersrelated = new usersrelated();
        if ($cryptor->verifySecureString($hash, 'sha512')) {
            list($expires, $relateTo) = $cryptor->getSecureData($hash);
            if ($expires < time()) {
                $this->messageHelper->pushError('Colony link has expired!');
            } else {
                if ($usersrelated->insertUserRelation($relateTo)) {
                    $this->messageHelper->pushMessage('Colony link successfully established!');
                } else {
                    $this->messageHelper->pushError('Colony link failed. Perhaps that link already exists.');
                }
            }
            $this->redirect(BASEPATH . '/hive');
        }
    }

}

?>

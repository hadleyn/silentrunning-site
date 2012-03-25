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
        $headHelper->addScript('jquery-ui-timepicker-addon');
        $headHelper->addScript('tools');
    }

    public function invoke() {
        $this->viewData['colonyLinksList'] = $this->bufferedControllerCall('createColonyLinksList');
        $this->loadView('tools');
    }

    
    /*
     * Ajax callbacks
     */
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
        $linkKey = $cryptor->createSecureString(array($expires, user::getCurrentUserID()), 'sha512');
        $colonyLink->createColonyLink($linkKey, $expires);
        $result['link'] = 'http://'.HOST . BASEPATH . '/colony/addlink/' . $linkKey;
        $result['colonyLinksList'] = $this->bufferedControllerCall('createColonyLinksList');
        echo json_encode($result);
    }
    
    public function deleteColonyLink_ajax() {
        $colonyLink = new colonylink();
        $colonyLink->deleteColonyLink(Input::post('linkid'));
        
    }

    /*
     * Protected callbacks
     */
    
    protected function createColonyLinksList() {
        $colonyLink = new colonylink();
        $this->viewData['colonyLinks'] = $colonyLink->getExistingColonyLinks();
        $this->loadView('colonylinkslist');
    }
}

?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hive
 *
 * @author smarkoski
 */
class hive extends HiveAuth {

//    protected $user;

    public function __construct() {
        parent::__construct();
        $headHelper = HeadHelper::getInstance();
        $headHelper->addScript('hive');
        $headHelper->addScript('content');
        $headHelper->addScript('alerts');

        $headHelper->addCSS('hivestyle');
    }

    public function invoke() {
        $hivemodel = new hivemodel();
        $hivemodel->partitionContent();
        $this->viewData['contentCreationForm'] = $this->bufferedControllerCall('createContentCreationForm');
        $this->viewData['hiveContent'] = $this->bufferedControllerCall('createHiveContents', array($hivemodel));
        $this->viewData['addCommentForm'] = $this->bufferedControllerCall('createCommentForm');
        $this->loadView('hive');
    }

    public function logout_precontroller() {
        $cookie = new Cookie();
        try {
            $cookie->clear('sr_user');
            $this->messageHelper->pushMessage('Successfully logged out of the hive!');
        } catch (CookieDataIOException $e) {
            $this->messageHelper->pushError('Uh oh, something went wrong. Please verify that you have been logged out or try again');
        }

        $this->redirect(Configuration::read('basepath'));
    }

    public function login_precontroller() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('handle'), 'handle'));
        $validator->addRule(new authenticateuser(Input::post('handle'), Input::post('password')));
        $this->messageHelper->pushError($validator->run());
        if ($this->messageHelper->hasErrors()) {
            $this->redirect(Configuration::read('basepath'));
        } else {
            $this->precontroller();
        }
    }

    public function register_precontroller() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new alphanumeric(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new uniqueusername(Input::post('registerHandle'), 'handle'));
        $validator->addRule(new required(Input::post('registerPassword'), 'password'));
        $validator->addRule(new matches(Input::post('registerPassword'), Input::post('registerPasswordConf'), 'password', 'password confirm'));
        $validator->addRule(new recaptcha(Input::post('recaptcha_challenge_field'), Input::post('recaptcha_response_field')));
        $this->messageHelper->pushError($validator->run());
        if ($this->messageHelper->hasErrors()) {
            $this->redirect(Configuration::read('basepath'));
        } else {
            $user = new User();
            $user->handle = Input::post('registerHandle');
            $user->password = Input::post('registerPassword');
            $user->insertNewUser();
            $user->authenticateHandlePassword(Input::post('registerHandle'), Input::post('registerPassword'));
        }
        $this->precontroller();
    }

    /*
     * 
     * Begin Ajax callbacks
     * 
     */

    public function createContent_ajax() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('content'), 'content'));
        $this->messageHelper->pushError($validator->run());
        $result['errors'] = '';
        if ($this->messageHelper->hasErrors()) {
            $result['errors'] = $this->messageHelper->showMessages(FALSE);
        } else {
            $content = new textcontent();
            $content->ownerid = $this->user->userid;
            $content->parentid = 0;
            $content->content_data = Input::post('content');
            try {
                $content->insertContent();
            } catch (Exception $e) {
                $result['errors'] = $e->getMessage();
            }
        }
        echo json_encode($result);
    }

    public function refreshContent_ajax() {
        $hivemodel = new hivemodel();
        $hivemodel->partitionContent();
        $result['hiveContent'] = $this->bufferedControllerCall('createHiveContents', array($hivemodel));
        echo json_encode($result);
    }

    public function updateHiveDepth_ajax() {
        $startDepth = Input::post('depth');
        $hivemodel = new hivemodel();
        $session = new Session();
        $content = new content();
        //Not so fast, do we already have hive content memorized?
//        try {
//            $hivemodel->content = $session->read('currentHiveContent');
//        } catch (SessionDataIOException $e) {
//            $hivemodel->content = null;
//        }
        $hivemodel->partitionContent($startDepth);
        $result['newhive'] = $this->bufferedControllerCall('createHiveContents', array($hivemodel));
        $result['atRoot'] = 'yes';
        echo json_encode($result);
    }

    public function updateContentCoords_ajax() {
        $content = new content();
        $content->getContent(Input::post('id'));
        $content->x = Input::post('x');
        $content->y = Input::post('y');
        $content->storeCoordinates();
    }

    /**
     * The add comment ajax callback. This functions almost identically to the
     * add content function, but it adds the content as a comment of another
     * parent content.
     * 
     * @generates AlertEvent An alert event is generated by the add comment 
     */
    public function addComment_ajax() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('comment'), 'comment'));
        $this->messageHelper->pushError($validator->run());
        $result['errors'] = '';
        if ($this->messageHelper->hasErrors()) {
            $result['errors'] = $this->messageHelper->showMessages(FALSE);
        } else {
            $content = new textcontent();
            $content->ownerid = $this->user->userid;
            $content->content_data = Input::post('comment');
            $content->parentid = Input::post('parentID');
            try {
                $content->insertContent();
                $alert = new newcommentalert();
                $alert->sendAlert($content);
            } catch (Exception $e) {
                $result['errors'] = $e->getMessage();
            }
        }
        echo json_encode($result);
    }

    public function getAlerts_ajax() {
        $alerts = $this->user->getAlerts();
        $result['alertIndicator'] = $this->bufferedControllerCall('createAlertIndicator', array($alerts));
        echo json_encode($result);
    }

    public function showComments_ajax() {
        $hivemodel = new hivemodel();
        $parentContent = new content();
        $parentContent->getContent(Input::post('parentid'));
        $children = $parentContent->getContentAndChildren(Input::post('parentid'), 0);
        $hivemodel->content = $children;
//        $hivemodel->partitionContent(); //Let's not partition comments
        $hivemodel->setCommentLayers();
        $result['hiveContent'] = $this->bufferedControllerCall('createHiveContents', array($hivemodel));
//        $session = new Session();
//        $session->write('currentHiveContent', $hivemodel->getCurrentIDList(), TRUE);
        echo json_encode($result);
    }

    public function closeComments_ajax() {
        $hivemodel = new hivemodel();
        $content = new content();
        $content->getContent(Input::post('parentID'));
        if ($content->parentid != 0) {
            $parentContent = new Content();
            $parentContent->getContent($content->parentid);
            $children = $parentContent->getContentAndChildren($content->parentid, 0);
            $hivemodel->content = $children;
            $hivemodel->setCommentLayers();
            $result['newhive'] = $this->bufferedControllerCall('createHiveContents', array($hivemodel));
            $result['atRoot'] = 'no';
            echo json_encode($result);
        } else {
            $this->updateHiveDepth_ajax(); //no more layers above us, go back to the default
        }
    }
    
    /*
     * 
     * Begin protected subview creation methods
     * 
     */

    protected function createContentCreationForm() {
        $this->loadView('contentcreationform');
    }

    protected function createHiveContents($hive) {
//        $textContent = new textcontent();

        $this->viewData['hivemodel'] = $hive;
        $this->loadView('hivecontents');
    }

    protected function createCommentForm() {
        $this->loadView('addcomment');
    }

    protected function createAlertIndicator($alerts) {
        $this->viewData['alerts'] = $alerts;
        $this->loadView('alertindicator');
    }

}

?>

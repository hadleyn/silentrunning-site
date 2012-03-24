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
        $headHelper->addScript('content');

        $headHelper->addScript('hive');
        $headHelper->addCSS('hivestyle');
    }

    public function invoke() {
        $this->viewData['contentCreationForm'] = $this->bufferedControllerCall('createContentCreationForm');
        $this->viewData['hiveContent'] = $this->bufferedControllerCall('createHiveContents');
        $this->loadView('hive');
    }

    public function logout_precontroller() {
        $cookie = new Cookie();
        try {
            $cookie->clear('sr_user');
        } catch (CookieDataIOException $e) {
            //nothing to do here
        }
        $this->redirect(Configuration::read('basepath'));
    }

    public function login_precontroller() {
        $validator = new Validator();
        $validator->addRule(new required(Input::post('handle'), 'handle'));
        $validator->addRule(new authenticateuser(Input::post('handle'), Input::post('password')));
        $this->errorHelper->pushError($validator->run());
        if ($this->errorHelper->hasErrors()) {
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
        $this->errorHelper->pushError($validator->run());
        if ($this->errorHelper->hasErrors()) {
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
        $this->errorHelper->pushError($validator->run());
        $result['errors'] = '';
        if ($this->errorHelper->hasErrors()) {
            $result['errors'] = $this->errorHelper->showErrors(FALSE);
        } else {
            $content = new content();
            $content->ownerid = $this->user->userid;
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
        $result['hiveContent'] = $this->bufferedControllerCall('createHiveContents');
        echo json_encode($result);
    }

    public function updateHiveDepth_ajax() {
        $depth = Input::post('depth');
        $hivemodel = new hivemodel(); //this automatically builds a partitioned list
    }

    public function updateContentCoords_ajax() {
        $content = new content();
        $content->getContent(Input::post('id'));
        $content->x = Input::post('x');
        $content->y = Input::post('y');
        $content->storeCoordinates();
    }

    /*
     * 
     * Begin protected subview creation methods
     * 
     */

    protected function createContentCreationForm() {
        $this->loadView('contentcreationform');
    }

    protected function createHiveContents() {
//        $textContent = new textcontent();
        $hivemodel = new hivemodel();
        $hivemodel->partitionContent();
        $this->viewData['hivemodel'] = $hivemodel;
        $this->loadView('hivecontents');
    }

}

?>

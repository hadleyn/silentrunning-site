<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HeaderBuilder
 *
 * @author smarkoski
 */
class HeaderBuilder implements Task {

    private $headHelper;

    public function __construct(){
        $this->headHelper = HeadHelper::getInstance();
        
        $this->setupHeadHelper();
    }

    public function execute(){
        echo $this->headHelper->generateHead();
        $indexController = new index();
        echo $indexController->bufferedControllerCall('header');
    }


    private function setupHeadHelper(){
        $this->headHelper->setTitle('silentrunning');
        $this->headHelper->addCSS('style');
        $this->headHelper->addCSS('jquery.svg.min');
        $this->headHelper->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', 0, TRUE);
        $this->headHelper->addScript('jquerysvg/jquery.svg', 1);
        $this->headHelper->addScript('jquery-ui-1.8.18.custom.min', 2);
        $this->headHelper->addCSS('custom-theme/jquery-ui');
        $this->headHelper->addScript('menu');
    }

}
?>

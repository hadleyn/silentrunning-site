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
        Loader::loadUtility('HeadHelper');
        $this->headHelper = new HeadHelper();

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
        $this->headHelper->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', TRUE);
        $this->headHelper->addScript('menu');
    }

}
?>

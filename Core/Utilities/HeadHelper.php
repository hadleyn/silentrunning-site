<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HeadHelper
 *
 * @author smarkoski
 */
class HeadHelper {

    private $css;
    private $script;
    private $meta;
    private $htmlTitle;

    public function __construct() {
        $this->css = array();
        $this->script = array();
        $this->meta = array();
    }

    public function setTitle($title) {
        $this->htmlTitle = $title;
    }

    public function addCSS($css) {
        $this->css[] = $css;
    }

    public function addScript($script) {
        $this->script[] = $script;
    }

    public function addMeta($name, $data) {
        $this->meta[$name] = $data;
    }

    public function generateHead() {
        ob_start();
        $temp['css'] = $this->css;
        $temp['script'] = $this->script;
        $temp['meta'] = $this->meta;
        $temp['title'] = $this->htmlTitle;
        extract($temp);
        include 'Core/HTML/HeaderBlock.php';
        $head = ob_get_contents();
        ob_end_clean();

        return $head;
    }

}
?>

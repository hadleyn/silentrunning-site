<?php
define('TEXT', 'TEXT');
define('IMAGE', 'IMAGE');
define('VIDEO', 'VIDEO');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of content
 *
 * @author smarkoski
 */
interface content {
    
    public function display();
    public function deleteContent($id);
    public function updateContent($id);
    public function getContent($id);
    public function getAllContent($depth);
    public function getOwner();
    public function setZ($value);
    public function getZ();
    public function setOpacity($value);
    public function getOpacity();
    
    
}

?>

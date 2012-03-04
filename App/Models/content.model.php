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
    
    public function displayContent();
    public function deleteContent($id);
    public function updateContent($id);
    public function getContent($id);
    public function getOwner();
    
}

?>

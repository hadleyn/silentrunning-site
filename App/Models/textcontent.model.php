<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of textcontent
 *
 * @author smarkoski
 */
class textcontent extends content {

    public function insertContent() {
        $db = DB::instance();
        $query = 'INSERT INTO content (ownerid, content_type, content_data, created, modified) VALUES (?, "' . TEXT . '", ?, NOW(), NOW())';
        $db->query($query, array('i', 's'), array($this->ownerid, $this->content_data));
        $this->contentid = $db->insert_id;
        $this->storeCoordinates();
    }

    public function display() {
        echo $this->content_data;
    }

}

?>

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
        $query = 'INSERT INTO content (ownerid, parentid, content_type, content_data, created, modified) VALUES (?, ?, "' . TEXT . '", ?, NOW(), NOW())';
        $db->query($query, array('i', 'i', 's'), array($this->ownerid, $this->parentid, $this->content_data));
        $insertid = $db->insert_id;
        $this->contentid = $insertid;
        $query = 'Insert into content_ancestors (contentid, ancestorid) (SELECT ?, ?) UNION (SELECT contentid, ancestorid from content_ancestors where contentid = ?)';
        $parentid = $this->parentid;
        if ($parentid == 0) {
            $parentid = $insertid;
        }
        $db->query($query, array('i', 'i', 'i'), array($insertid, $parentid, $insertid));
        $this->storeCoordinates();
    }

    public function display() {
        echo '<text class="content" x="0" y="30">'.$this->content_data.'</text>';
    }

}

?>

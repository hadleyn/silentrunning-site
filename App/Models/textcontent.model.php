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
class textcontent extends CoreModel implements content {

    protected $contentid;
    protected $ownerid;
    protected $content_type;
    protected $content_data;
    protected $created;
    protected $modified;
    protected $zindex;
    protected $opacity;

    public function getContent($id) {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE contentid=? AND content_type="'.TEXT.'"';
        $db->query($query, array('i'), array($id));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        $this->populate($result);
    }

    public function getAllContent($depth) {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE content_type = "'.TEXT.'" AND DATE(modified) > DATE_SUB(CURRENT_DATE(), INTERVAL ' . $depth . ' DAY) ORDER BY modified DESC';
        $db->query($query);
        $allContent = array();
        while ($resultRow = $db->fetchResult()) {
            $tc = new textcontent();
            $tc->populate($resultRow);
            $allContent[] = $tc;
        }
        return $allContent;
    }

    public function deleteContent($id) {
        $db = DB::instance();
        $query = 'DELETE FROM content where contentid=?';
        $db->query($query, array('i'), array($id));
    }

    public function display() {
        echo $this->content_data;
    }

    public function getOwner() {
        $user = new User();
        $user->getUserByHandle($this->ownerid);
        return $user;
    }

    public function getZ() {
        return $this->zindex;
    }

    public function setZ($value) {
        $this->zindex = $value;
    }

    public function updateContent($id) {
        
    }

    public function insertContent() {
        $db = DB::instance();
        $query = 'INSERT INTO content (ownerid, content_type, content_data, created, modified) VALUES (?, "' . TEXT . '", ?, NOW(), NOW())';
        $db->query($query, array('i', 's'), array($this->ownerid, $this->content_data));
    }

    public function getAll() {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE content_type = "' . TEXT . '" ORDER BY modified';
        $db->query($query);
        $results = array();
        while ($resultRow = $db->fetchResult()) {
            $tc = new textcontent();
            $tc->populate($resultRow);
            $results[] = $tc;
        }

        return $results;
    }

    public function getOpacity() {
        return $this->opacity;
    }

    public function setOpacity($value) {
        $this->opacity = $value;
    }

}

?>

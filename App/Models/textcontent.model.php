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
    
    
    public function getContent($id) {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE contentid=?';
        $db->query($query, array('i'), array($id));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        $this->populate($result);
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
        return $this->ownerid;
    }
    
    public function getZ() {
        
    }

    public function setZ() {
        
    }

    public function updateContent($id) {
        
    }

    public function insertContent() {
        $db = DB::instance();
        $query = 'INSERT INTO content (ownerid, content_type, content_data, created, modified) VALUES (?, "'.TEXT.'", ?, NOW(), NOW())';
        $db->query($query, array('i', 's'), array($this->ownerid, $this->content_data));
    }
    
    public function getAll() {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE content_type = "'.TEXT.'" ORDER BY created';
        $db->query($query);
        $results = array();
        while ($resultRow = $db->fetchResult()) {
            $tc = new textcontent();
            $tc->populate($resultRow);
            $results[] = $tc;
        }
        
        return $results;
    }

    
    
}

?>

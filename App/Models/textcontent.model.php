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
    protected $x;
    protected $y;

    public function __construct() {
        $this->x = 0;
        $this->y = 0;
    }

    public function getContent($id) {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE contentid=? AND content_type="' . TEXT . '"';
        $db->query($query, array('i'), array($id));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        $this->populate($result);
    }

    public function getAllContent($depth) {
        $db = DB::instance();
        $query = 'SELECT content.contentid, ownerid, content_type, content_data, created, modified, x, y 
                    FROM content 
                    LEFT JOIN content_coords 
                    ON content_coords.userid = content.ownerid AND content_coords.contentid = content.contentid AND content_coords.userid = '.user::getCurrentUserID().'
                    WHERE content_type = "' . TEXT . '" AND DATE(modified) > DATE_SUB(CURRENT_DATE(), INTERVAL ' . $depth . ' DAY) ORDER BY modified DESC';
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
        $this->contentid = $db->insert_id;
        $this->storeCoordinates();
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

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function setX($value) {
        $this->x = $value;
    }

    public function setY($value) {
        $this->y = $value;
    }

    public function storeCoordinates() {
        $db = DB::instance();
        $userid = user::getCurrentUserID();
        $hash = md5($userid . $this->contentid);
        $query = 'INSERT INTO content_coords (userid, contentid, hash, x, y) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE x=?, y=?';
        $db->query($query, array('i', 'i', 's', 'i', 'i', 'i', 'i'), array($userid, $this->contentid, $hash, $this->getX(), $this->getY(), $this->getX(), $this->getY()));
    }

}

?>

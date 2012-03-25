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
class content extends CoreModel {

    protected $contentid;
    protected $ownerid;
    protected $content_type;
    protected $content_data;
    protected $created;
    protected $modified;
    protected $opacity;
    protected $x;
    protected $y;
    protected $z;
    protected $scale;
    protected $styleString;

    public function __construct() {
        $this->x = 0;
        $this->y = 0;
    }

    public function display() {
        //should be overridden
    }

    public function deleteContent($id) {
        $db = DB::instance();
        $query = 'DELETE FROM content where contentid=?';
        $db->query($query, array('i'), array($id));
    }

    public function updateContent($id)
    {
        
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
                    ON content_coords.contentid = content.contentid AND content_coords.userid = ' . user::getCurrentUserID() . '
                    WHERE DATE(modified) > DATE_SUB(CURRENT_DATE(), INTERVAL ' . $depth . ' DAY) AND ownerid=' . user::getCurrentUserID() . ' OR
                    ownerid = (SELECT relatedtouserid FROM users_related WHERE userid=' . user::getCurrentUserID() . ') ORDER BY modified DESC';
        $db->query($query);
        $allContent = array();
        while ($resultRow = $db->fetchResult()) {
            $tc = new textcontent();
            $tc->populate($resultRow);
            $allContent[] = $tc;
        }
        return $allContent;
    }

    public function getOwner() {
        $user = new User();
        $user->getUserByHandle($this->ownerid);
        return $user;
    }

    public function insertContent() {
        $db = DB::instance();
        $query = 'INSERT INTO content (ownerid, content_type, content_data, created, modified) VALUES (?, "'.TEXT.'", ?, NOW(), NOW())';
        $db->query($query, array('i', 's'), array($this->ownerid, $this->content_data));
    }

    public function storeCoordinates() {
        $db = DB::instance();
        $userid = user::getCurrentUserID();
        $hash = md5($userid . $this->contentid);
        $query = 'INSERT INTO content_coords (userid, contentid, hash, x, y) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE x=?, y=?';
        $db->query($query, array('i', 'i', 's', 'i', 'i', 'i', 'i'), array($userid, $this->contentid, $hash, $this->x, $this->y, $this->x, $this->y));
    }
    
    public function getStyleString() {
        return 'top: '.$this->y.'px; '.
                'left: '.$this->x.'px; '.
                'z-index: '.$this->z.'; '.
                'opacity: '.$this->opacity.'; '.
                'transform: scale('.$this->scale.'); '.
                '-ms-transform: scale('.$this->scale.'); '.
                '-webkit-transform: scale('.$this->scale.'); '.
                '-o-transform: scale('.$this->scale.'); '.
                '-moz-transform: scale('.$this->scale.');';
    }

}

?>

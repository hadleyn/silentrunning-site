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
abstract class content extends CoreModel {

    protected $contentid;
    protected $ownerid;
    protected $parentid;
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
    protected $isPseudoRoot; //a flag to let us know if this piece of content is the root in this context

    public function __construct() {
        $this->x = 0;
        $this->y = 0;
        $this->isPseudoRoot = FALSE;
    }

    abstract public function display();

    public static function getOrphanedContent() {
        $db = DB::instance();
        $query = 'SELECT * FROM content AS mycontent WHERE mycontent.ownerid=? AND mycontent.parentid != 0 AND NOT EXISTS (SELECT * FROM content WHERE content.contentid = mycontent.parentid)';
        $db->query($query, array('i'), array(user::getCurrentUserID()));
        $orphanedContent = array();
        while ($result = $db->fetchResult()) {
            switch ($result['content_type']) {
                case TEXT:
                    $content = new textcontent();
                    break;
                default:
                    $content = null;
            }
            $content->populate($result);
            $orphanedContent[] = $content;
        }
        return $orphanedContent;
    }

    public static function deleteContent($id) {
        $db = DB::instance();
        $query = 'DELETE FROM content where contentid=?';
        $db->query($query, array('i'), array($id));
    }

    abstract public function updateContent($id);

    abstract public function insertContent();

    public static function getContent($id) {
        $db = DB::instance();
        $query = 'SELECT * FROM content WHERE contentid=?';
        $db->query($query, array('i'), array($id));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        switch ($result['content_type']) {
            case TEXT:
                $content = new textcontent();
                break;
            default:
                $content = null;
        }
        $content->populate($result);
        return $content;
    }

    public static function getContentAndChildren($id, $startDepth) {
        $db = DB::instance();
        $query = 'SELECT content.contentid FROM content WHERE content.parentid=? OR content.contentid=? ORDER BY modified DESC';
        $db->query($query, 'i,i', array($id, $id));
        $contentIDs = array();
        while ($resultRow = $db->fetchResult()) {
            $contentIDs[] = $resultRow['contentid'];
        }
        $content = self::getAllContent($startDepth, 'hours', $contentIDs);
        $count = count($content);
        for ($i = 0; $i < $count; $i++) {
            if ($content[$i]->contentid == $id) {
                $content[$i]->isPseudoRoot = TRUE;
            }
        }
        return $content;
    }

    /**
     *
     * @param type $startDepth
     * @param type $unit
     * @param type $inList The inlist default is just parentid=0. You can replace this with "something IN (some, list, of, things)"
     * @return \content 
     */
    public static function getAllContent($startDepth, $unit = 'hours', $inList = 'parentid = 0') {
        if (is_array($inList)) {
            $inList = 'content.contentid IN (' . implode(',', $inList) . ')';
        }
        $db = DB::instance();
        $startTime = strtotime('-' . $startDepth . ' ' . $unit);
        $depth = strtotime('-' . Configuration::read('default_hive_depth') . ' ' . $unit, $startTime);
        $query = 'SELECT content.contentid, content.parentid, ownerid, content_type, content_data, created, modified, x, y 
                    FROM content
                    LEFT JOIN content_coords 
                    ON content_coords.contentid = content.contentid AND content_coords.userid = ' . user::getCurrentUserID() . '
                    WHERE ' . $inList . ' AND (UNIX_TIMESTAMP(modified) <= ' . $startTime . ' AND UNIX_TIMESTAMP(modified) > ' . $depth . ') AND (ownerid=' . user::getCurrentUserID() . ' OR
                    ownerid = (SELECT relatedtouserid FROM users_related WHERE userid=' . user::getCurrentUserID() . ')) ORDER BY modified DESC';
        $db->query($query);
        $allContent = array();
        while ($resultRow = $db->fetchResult()) {
            switch ($resultRow['content_type']) {
                case TEXT:
                    $content = new textcontent();
                    break;
                default:
                    $content = null;
            }
            $content->populate($resultRow);

            $allContent[] = $content;
        }
        return $allContent;
    }

    public function getOwner() {
        $user = new User();
        $user->getUserByHandle($this->ownerid);
        return $user;
    }

    public function storeCoordinates() {
        $db = DB::instance();
        $userid = user::getCurrentUserID();
        $hash = md5($userid . $this->contentid);
        $query = 'INSERT INTO content_coords (userid, contentid, hash, x, y) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE x=?, y=?';
        $db->query($query, array('i', 'i', 's', 'i', 'i', 'i', 'i'), array($userid, $this->contentid, $hash, $this->x, $this->y, $this->x, $this->y));
    }

    public function getStyleString() {
        return 'top: ' . $this->y . 'px; ' .
                'left: ' . $this->x . 'px; ' .
                'z-index: ' . $this->z . '; ' .
                'opacity: ' . $this->opacity . '; ';
//                'transform: scale('.$this->scale.'); '.
//                '-ms-transform: scale('.$this->scale.'); '.
//                '-webkit-transform: scale('.$this->scale.'); '.
//                '-o-transform: scale('.$this->scale.'); '.
//                '-moz-transform: scale('.$this->scale.');';
    }

    public function getCenterX() {
        return ($this->x + 270) / 2;
    }

    public function getCenterY() {
        return ($this->y + 125) / 2;
    }

    public function childCount() {
        $db = DB::instance();
        $query = 'SELECT COUNT(*) AS c FROM content WHERE parentid=?';
        $db->query($query, 'i', array($this->contentid));
        $result = $db->fetchResult();
        $db->cleanupConnection();
        return $result['c'];
    }

}

?>

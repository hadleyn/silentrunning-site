<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of colony
 *
 * @author smarkoski
 */
class colonylink extends CoreModel {
    
    protected $linkid;
    protected $ownerid;
    protected $link_key;
    protected $expires;
    
    
    public function getExpires($format='m-d-Y h:i a') {
        return date($format, $this->expires);
    }
    
    public function getExistingColonyLinks() {
        $db = DB::instance();
        $query = 'SELECT linkid, ownerid, link_key, UNIX_TIMESTAMP(expires) AS expires FROM colony_links WHERE ownerid = '.user::getCurrentUserID();
        $db->query($query);
        $colonyLinks = array();
        while ($resultRow = $db->fetchResult()) {
            $colonyLink = new colonylink();
            $colonyLink->populate($resultRow);
            $colonyLinks[] = $colonyLink;
        }
        return $colonyLinks;
    }
    
    public function createColonyLink($linkKey, $expires) {
        $db = DB::instance();
        $query = 'INSERT INTO colony_links (ownerid, link_key, expires) VALUES (?, ?, FROM_UNIXTIME(?))';
        $db->query($query, 'i,s,s', array(user::getCurrentUserID(), $linkKey, $expires));
    }
    
    public function deleteColonyLink($linkid) {
        $db = DB::instance();
        $query = 'DELETE FROM colony_links WHERE linkid=?';
        $db->query($query, 'i', array($linkid));
    }
    
    
    
}

?>

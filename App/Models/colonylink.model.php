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
    
    protected $userid;
    protected $relatedtouserid;
    
    public function createColonyLink($linkKey, $expires) {
        $db = DB::instance();
        $query = 'INSERT INTO colony_links (ownerid, link_key, expires) VALUES (?, ?, FROM_UNIXTIME(?))';
        $db->query($query, 'i,s,s', array(user::getCurrentUserID(), $linkKey, $expires));
    }
    
    public function insertUserRelation($relatedTo) {
        $db = DB::instance();
        $query = 'INSERT INTO users_related (userid, relatedtouserid) VALUES (?, ?)';
        $db->query($query, 'i,i', array(user::getCurrentUserID(), $relatedTo));
        $db->query($query, 'i,i', array($relatedTo, user::getCurrentUserID()));
    }
    
}

?>

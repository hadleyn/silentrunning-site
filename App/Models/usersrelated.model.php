<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usersrelated
 *
 * @author smarkoski
 */
class usersrelated extends CoreModel {
    
    protected $userid;
    protected $relatedtouserid;
    
    
    /**
     *
     * @param int $relatedTo The user to relate the current user to
     * @return boolean Whether or not the linking was successful
     */
    public function insertUserRelation($relatedTo) {
        $db = DB::instance();
        $currentUser = user::getCurrentUserID();
        $query = 'INSERT INTO users_related (userid, relatedtouserid, hash) VALUES (?, ?, ?)';
        try {
            $db->query($query, 'i,i,s', array($currentUser, $relatedTo, md5($currentUser. $relatedTo)));
            $db->query($query, 'i,i,s', array($relatedTo, $currentUser, md5($relatedTo. $currentUser)));
        } catch (MysqliQueryExecutionException $e) {
            return FALSE;
        }
        return TRUE;
    }
}

?>

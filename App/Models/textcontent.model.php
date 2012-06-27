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

    const LINE_LENGTH = 60;
    
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
//        $this->parseURLs();
        $words = explode(' ', $this->content_data);
        $echo = '<text class="content" x="0" y="40">';
        while (isset($words[0])) {
            $line = '';
            if (strlen($words[0]) >= self::LINE_LENGTH) {
                $line = array_shift($words);
            }
            while (strlen($line) + strlen($words[0]) < self::LINE_LENGTH) {
                $line .= ' ' . array_shift($words);
            }
            $echo .= '<tspan x="0" dy="12">'.$line.'</tspan>';
        }
        echo $echo . '</text>';
    }
    
    private function parseURLs() {
        //Found this regex at http://daringfireball.net/2010/07/improved_regex_for_matching_urls
//        preg_match('@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'\".,<>?«»“”‘’]))@i', $this->content_data, $matches);
//        count($matches);
//        <a xlink:href="#">
//                    <text class="addComment" x="190" y="15">add comment</text>
//                </a>
    }

    public function updateContent($id) {
        
    }

}

?>

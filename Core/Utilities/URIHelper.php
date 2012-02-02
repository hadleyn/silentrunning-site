<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of URIHelper
 *
 * @author smarkoski
 */
class URIHelper {
    
    /**
     * Get a sanitized version of the request URI
     * 
     * @return string The request URI filtered by FILTER_SANITIZE_URL 
     */
    public static function getRequestURI()
    {
        return filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    }

    public static function getURIArray()
    {
        $uri = self::getRequestURI();
        $uri = preg_replace('@\/$@', '', $uri);
        $array = explode('/', $uri);

        return $array;
    }

    public static function getURIElementAtIndex($index)
    {
        $uri = self::getURIArray();

    }

}
?>

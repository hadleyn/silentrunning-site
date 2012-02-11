<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author smarkoski
 */
class DB {

    private static $instance;

    protected function __construct(){}

    public static function instance(){
        if (!isset(self::$instance)){
            self::$instance = new DB();
        }
        return self::$instance;
    }



}
?>

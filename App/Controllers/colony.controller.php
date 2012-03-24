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

namespace App\Controllers {

    class colony extends HiveAuth {

        public function __construct() {
            parent::__construct();
        }

        public function addlink_precontroller($test) {
            parent::precontroller();
            $colony = new App\Model\colony();
            $me = $test;
        }

    }

}
?>

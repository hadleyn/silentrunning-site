<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hive
 * 
 * The hive model acts as a collection of content. It manages things about the 
 * content that allows content to display properly inside the hive.
 *
 * @author smarkoski
 */
class hivemodel extends CoreModel {

    protected $layers;
    protected $hiveContent;

    public function __construct() {
        $this->layers = array();
        $this->hiveContent = array();
    }

    /**
     * Add a single piece of content to the hive
     * 
     * @param content $content 
     */
    public function addContent($content) {
        if (is_array($content)) {
            $this->hiveContent = array_merge($this->hiveContent, $content);
        } else {
            $this->hiveContent[] = $content;
        }
    }
    
    /**
     * This function partitions all the content into the layers. 
     */
    private function partitionContent() {
        $layerCount = $this->calculateLayers();
    }
    
    /**
     * This function will take all the content within the hive and calculate 
     * how many layers we should use to show it.
     * 
     * @return int The number of layers 
     */
    private function calculateLayers() {
        
    }

}

?>

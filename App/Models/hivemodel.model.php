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
//    protected $hiveContent;
    protected $textContent;
    protected $videoContent;
    protected $photoContent;

    public function __construct() {
        $this->layers = array();
//        $this->hiveContent = array();
        $this->textContent = new textcontent();
        $this->partitionContent();
    }

    public function reduceToDepth($depth) {
//        $i = ;
    }
    
    /**
     * Add a single piece of content to the hive
     * 
     * @param content $content 
     */
//    public function addContent($content) {
//        if (is_array($content)) {
//            $this->hiveContent = array_merge($this->hiveContent, $content);
//        } else {
//            $this->hiveContent[] = $content;
//        }
//        $this->partitionContent();
//    }
    
    /**
     * This function partitions all the content into the layers. 
     */
    private function partitionContent() {
        $layers = $this->calculateLayers();
        //Now assign z indexes to the layers
        $maxZ = 30;
        $opacity = 1;
        foreach ($layers as $l) {
            foreach ($l as $lc) {
                $lc->setZ($maxZ);
                $lc->setOpacity($opacity);
                $this->layers[] = $lc;
            }
            $maxZ--;
            $opacity = $opacity / 2; //the further back in time we go, the more faded the content is.
        }
        
    }
    
    /**
     * This function will take all the content within the hive and calculate 
     * how many layers we should use to show it.
     * 
     * @return int The number of layers 
     */
    private function calculateLayers() {
        //find the range of dates we are trying to show
        
        $allTextContent = $this->textContent->getAllContent(Configuration::read('default_hive_depth'));
        //$allVideoContent = ...
        //$allImageContent = ...
        
        $layers = array_chunk($allTextContent, Configuration::read('hive_layer_size'));
        
        return $layers;
        
    }

}

?>

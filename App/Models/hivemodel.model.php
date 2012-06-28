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
    protected $content; //an array of content
    protected $videoContent;
    protected $photoContent;

    public function __construct() {
        $this->layers = array();
        $this->content = null;
    }

    /**
     *The purpose of this function is to generate a list of content IDs based on
     * what the hive model currently contains.
     * 
     * @return array An array of IDs 
     */
    public function getCurrentIDList(){
        $result = array();
        foreach ($this->content as $c){
            $result[] = $c->contentid;
        }
        return $result;
    }

    
    public function setCommentLayers() {
        foreach ($this->content as $c) {
            $this->layers[] = $c;
        }
    }
    
    /**
     * This function partitions all the content into the layers. 
     */
    public function partitionContent($startDepth = null) {
        $layers = $this->calculateLayers($startDepth);
        //Now assign z indexes to the layers
        $maxZ = 150;
        $opacity = 1;
        $scale = 1;
        foreach ($layers as $l) {
            foreach ($l as $lc) {
                if (!$lc->isPseudoRoot) {
                    $lc->z = $maxZ;
                    $lc->opacity = $opacity;
//                    $lc->scale = $scale;
                }
                $this->layers[] = $lc;
            $maxZ--;
            
            }
            
            $opacity = $opacity / 2; //the further back in time we go, the more faded the content is.
            $scale = $scale * 0.85; //the further back in time we go, the smaller the content gets
        }
    }

    /**
     * This function will take all the content within the hive and calculate 
     * how many layers we should use to show it.
     * 
     * @return int The number of layers 
     */
    private function calculateLayers($startDepth) {
        //find the range of dates we are trying to show
        if (!isset($startDepth)) {
            $startDepth = 0;
        }
        if ($this->content == null) {
            $allContent = content::getAllContent($startDepth, 'hours');
        } else {
            $allContent = $this->content;
        }
        //$allVideoContent = ...
        //$allImageContent = ...

        $layers = array_chunk($allContent, Configuration::read('hive_layer_size'));

        return $layers;
    }

}

?>

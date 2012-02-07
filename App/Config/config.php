<?php
/**
 * This config file holds values that are used by the framework. You can also
 * create your own config files and store them here and load them.
 */
/**
 * The basepath configuration variable represents the directory where the 
 * bootstrap will be located. Leave blank if the bootstrap is in the root.
 */
$basepath = array();
if (preg_match('/localhost/i', $_SERVER['SERVER_NAME']) > 0){
    $basepath = array('sr');
}
Configuration::write('basepath', $basepath);


/**
 * If you rewrite the URLs to remove the index.php from them, you should set
 * this to TRUE.
 */
Configuration::write('mod_rewrite_enabled', TRUE);

/**
 * Default Controller is the controller that is loaded when no controller
 * is specified by the URL
 */
Configuration::write('defaultController', 'index');

?>

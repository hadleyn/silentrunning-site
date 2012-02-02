<?php
/**
 * This is the main bootstrap for the SilentRunning Framework
 */

require_once 'Core/Utilities/Loader.php';


spl_autoload_register(null, false);
spl_autoload_extensions('.php');
spl_autoload_register(array('Loader', 'loadException'));
spl_autoload_register(array('Loader', 'loadUtility'));
spl_autoload_register(array('Loader', 'loadController'));

$controllerCommand = URIHelper::getURIElementAtIndex(URI_COMMAND);
echo 'controller '.$controllerCommand;
$controllerFile = 'App/Controller/'.$controllerCommand.'.php';

if (file_exists($controllerFile))
{
    
}

?>

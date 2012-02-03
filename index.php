<?php

/**
 * This is the main bootstrap for the SilentRunning Framework
 */
require_once 'Core/Controllers/CoreController.php';
require_once 'Core/Utilities/Loader.php';
require_once 'App/Config/config.php';

spl_autoload_register(null, false);
spl_autoload_extensions('.php');
spl_autoload_register(array('Loader', 'loadException'));
spl_autoload_register(array('Loader', 'loadUtility'));
spl_autoload_register(array('Loader', 'loadController'));

$uriArray = URIHelper::getURIArray();

//Shift off the basepath and discard it
foreach ($config['basepath'] as $c) {
    $devnull = array_shift($uriArray);
}

if (!$config['mod_rewrite_enabled']) {
    $devnull = array_shift($uriArray);
}

$controller = array_shift($uriArray);
$method = array_shift($uriArray);
$arguments = $uriArray;

echo 'controller: ' . $controller;
$controllerFile = 'App/Controllers/' . $controller . '.controller.php';

if (file_exists($controllerFile)) {
    $mainController = new $controller();
} else if (empty($controller)) {
    //Load the default controller
    $mainController = new $config['defaultController']();
} else {
    die('Missing controller: ' . $controllerFile);
}

//Now begin the controller chain

/*
 * Precontroller methods are first. These methods are expected to do any work that
 * must be completed prior to headers being written.
 */
$validMethod = 'precontroller';
if (method_exists($mainController, $method . '_precontroller')) {
    $validMethod = $method.'_precontroller';
}
wrap_call_user_func_array($mainController, $validMethod, $arguments);

$validMethod = 'invoke';
if (method_exists($mainController, $method . '_invoke')) {
    $validMethod = $method.'_invoke';
}
wrap_call_user_func_array($mainController, $validMethod, $arguments);

$validMethod = 'postcontroller';
if (method_exists($mainController, $method . '_postcontroller')) {
    $validMethod = $method.'_postcontroller';
}
wrap_call_user_func_array($mainController, $validMethod, $arguments);

function wrap_call_user_func_array($c, $a, $p) {
    switch (count($p)) {
        case 0: $c->{$a}();
            break;
        case 1: $c->{$a}($p[0]);
            break;
        case 2: $c->{$a}($p[0], $p[1]);
            break;
        case 3: $c->{$a}($p[0], $p[1], $p[2]);
            break;
        case 4: $c->{$a}($p[0], $p[1], $p[2], $p[3]);
            break;
        case 5: $c->{$a}($p[0], $p[1], $p[2], $p[3], $p[4]);
            break;
        default: call_user_func_array(array($c, $a), $p);
            break;
    }
}
?>

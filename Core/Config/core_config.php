<?php

require_once 'Core/Utilities/Configuration.php';
/*
 * This config holds things that apply to the framework core
 */
Configuration::write('view_path', 'App/Views/');

Configuration::write('controller_path', 'App/Controllers/');

Configuration::write('utility_path', 'Core/Utilities/');

Configuration::write('task_path', 'App/Tasks/');

Configuration::write('exception_path', 'Core/Exceptions/');

?>

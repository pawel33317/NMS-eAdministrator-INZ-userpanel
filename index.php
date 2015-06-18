<?php
error_reporting(E_ALL);
require 'config.php';


//This function is auto executing via php
spl_autoload_register(function ($class) {
     require LIBS . $class .".php";
});

// Load the Bootstrap!
$bootstrap = new Bootstrap();

// Optional Path Settings
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();

$bootstrap->init();
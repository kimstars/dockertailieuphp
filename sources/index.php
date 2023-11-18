<?php

/**
 * DO NOT MODIFY ANYTHING HERE
 */

session_start();

$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'DefaultController';

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

/**
 * Require vendor
 */
require('vendor/autoload.php');


/**
 * Dotenv
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Require necessary base file
 */
$phpFile = preg_replace('/index.php/', '', $_SERVER['PHP_SELF']);

$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';

define('BASE_PATH', $protocol . "://" . $_SERVER['HTTP_HOST'] . $phpFile);

/**
 * Require necessary base file
 */

require('helpers/index.php');

/**
 * Load controller tương ứng
 */
$module = $_GET['module'] == 'admin' ? 'Admin' : 'Web';
require "app/Controllers/$module/$controllerName.php";

/**
 * Require controller
 */

$controllerInstance = new $controllerName();

echo $controllerInstance->$action();
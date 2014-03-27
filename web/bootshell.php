<?php

if (!empty($_GET['debug']))
  ini_set('display_errors',1);error_reporting(E_ALL);

define('WEBROOT',dirname(__FILE__));
define('APP_PATH',WEBROOT.'/ace');

include APP_PATH.'/autoload.php';

\ace\Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

include APP_PATH.'/router.php';

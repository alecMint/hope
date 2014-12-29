<?php

if (!empty($_GET['debug'])) {
	ini_set('display_errors',1);
	error_reporting(E_ALL);
}

define('WEBROOT',dirname(__FILE__));
define('APP_PATH',WEBROOT.'/ace');
include APP_PATH.'/autoload.php';
use \ace\Ace;
define('REQUEST_PATH', rtrim( Ace::g($_SERVER, array('DOCUMENT_URI','REDIRECT_URL'), ''), '/' ));

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.local.php' );

use \ace\Router;
Router::route(REQUEST_PATH);


<?php

//ini_set('display_errors',1);error_reporting(E_ALL);

define('WEBROOT',dirname(__FILE__));
define('APP_PATH',WEBROOT.'/ace');

//include APP_PATH.'/helpers/common.php';
include APP_PATH.'/Ace.php';

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

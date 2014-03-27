<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

define('WEBROOT',dirname(__DIR__));
define('APP_PATH',WEBROOT.'/ace');

include APP_PATH.'/helpers/common.php';
include APP_PATH.'/Ace.php';

var_dump(APP_PATH);
var_dump(class_exists('Ace'));

\ace\Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

echo WEBROOT.'4444';
exit;
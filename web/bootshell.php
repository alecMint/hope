<?php

define('WEBROOT',dirname(__DIR__));

include WEBROOT.'/helpers/common.php';
include WEBROOT.'/helpers/Ace.php';

var_dump(class_exists('Ace'));exit;

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

echo WEBROOT.'4444';
exit;
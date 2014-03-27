<?php

define('WEBROOT',dirname(__DIR__));
exit 'hey: '.WEBROOT;

include WEBROOT.'/helpers/common.php';
include WEBROOT.'/helpers/Ace.php';

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );
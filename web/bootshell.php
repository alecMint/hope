<?php

define('WEBROOT',dirname(__DIR__));

include WEBROOT.'/helpers/common.php';
include WEBROOT.'/helpers/Ace.php';
echo WEBROOT.'2222';
exit;

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

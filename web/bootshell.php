<?php

define('WEBROOT',dirname(__DIR__));

echo WEBROOT.'wefwef';
exit;

include WEBROOT.'/helpers/common.php';
include WEBROOT.'/helpers/Ace.php';

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

<?php

define('WEBROOT',dirname(__DIR__));

include WEBROOT.'/helpers/common.php';

echo WEBROOT.'wefwefefwefwefewf';
exit;
include WEBROOT.'/helpers/Ace.php';

Ace::loadConfig( WEBROOT.'/config.php', WEBROOT.'/config.override.php' );

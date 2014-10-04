<?php
echo json_encode(array(
	't' => date('r'),
	'whoami' => str_replace("\n",'',`whoami`),
));
var_dump($_SERVER);
phpinfo();

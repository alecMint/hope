<?php

use \ace\Ace;

if (strpos(Ace::g($_SERVER,'DOCUMENT_URI',''),'/ace/api/') === 0) {
  \ace\controllers\Api::request(substr($_SERVER['DOCUMENT_URI'],strlen('/ace/api/')));
  exit;
}
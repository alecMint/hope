<?php
/*
https://dev.twitter.com/docs/auth/application-only-auth
*/
//ini_set('display_errors',1);
//error_reporting(E_ALL);

$key = '';
$secret = '';
$creds = rawurlencode($key).':'.rawurlencode($secret);
$creds =  base64_encode($creds);

$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Authorization: Basic $creds",
));
$r = curl_exec($ch);

echo $r;

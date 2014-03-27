<?php
/*
https://dev.twitter.com/docs/auth/application-only-auth
*/
ini_set('display_errors',1);
error_reporting(E_ALL);
echo 'hru';

$key = 'HFUeBKXV6OLp1PYXl3RVzQ';
$secret = 'kMBnmyGwgmcpWRNyXPPGDmMhRanKHkVOxDSkUU4M6Kc';
$creds =  base64_encode(rawurlencode($key).':'.rawurlencode($secret));


$ch = curl_init();
/*
curl_setopt($ch, CURLOPT_USERPWD, $creds);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
//curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth2/token?screen_name=twitterapi&count=2');
*/

curl_setopt($ch, CURLOPT_VERBOSE , true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Host: api.twitter.com",
  "User-Agent: My Twitter App v1.0.23",
  "Authorization: Basic $creds",
  "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
  "Content-Length: 29",
  "Accept-Encoding: gzip",
));

$r = curl_exec($ch);

echo 'sup<br />';
//echo "$creds<br />";
echo $r;


/*
curl -X POST --data 'grant_type=client_credentials' https://api.twitter.com/oauth2/token
SEZVZUJLWFY2T0xwMVBZWGwzUlZ6UTprTUJubXlHd2dtY3BXUk55WFBQR0RtTWhSYW5LSGtWT3hEU2tVVTRNNktj

*/

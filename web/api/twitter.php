<?php

try {
$key = 'HFUeBKXV6OLp1PYXl3RVzQ';
$secret = 'kMBnmyGwgmcpWRNyXPPGDmMhRanKHkVOxDSkUU4M6Kc';
$creds =  base64_encode(urlencode($key).':'.urlencode($secret));

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERPWD, $creds);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
$r = curl_exec($ch);

echo 'sup<br />';
echo $r;
} catch (Exception $e){echo "$e";}
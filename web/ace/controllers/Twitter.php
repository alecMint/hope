<?php
/*
https://dev.twitter.com/docs/auth/application-only-auth
*/

namespace ace\controllers;
use \ace\Ace;

class Twitter {

  public static function getOAuth2Token(){
    $key = Ace::getConfig('hopeTwitterAppKey');
    $secret = Ace::getConfig('hopeTwitterAppSecret');
    $creds = base64_encode(rawurlencode($key).':'.rawurlencode($secret));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Authorization: Basic $creds",
    ));
    return curl_exec($ch);
  }

}

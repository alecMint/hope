<?php
/*
https://dev.twitter.com/docs/auth/application-only-auth
*/

namespace ace\controllers;
use \ace\Ace;

class Twitter {

  public function getOAuth2Token(){
    $key = Ace::getConfig('hopeTwitterAppKey').'wef';
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
    $r = json_decode(curl_exec($ch));
    if (!is_object($r))
      throw new \Exception('unexpected response from twitter');
    if (!isset($r->access_token))
      throw new \Exception('missing access_token');
    return $r;
  }

}

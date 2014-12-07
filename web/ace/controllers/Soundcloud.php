<?php
/*
http://api.soundcloud.com/playlists/29344655.json?client_id=YOUR_CLIENT_ID
https://developers.soundcloud.com/docs/api/reference#tracks
*/

namespace ace\controllers;
use \ace\Ace;
use \ace\ControllerAbstract;

class Soundcloud extends ControllerAbstract {

  public function get(){
    $params = $this->getInput(array(
      'route' => true,
      'p' => false,
    ));
    if (!is_array($params['p']))
      $params['p'] = array();

    $url = 'https://http://api.soundcloud.com/'.$params['route'].'.json';
    $url .= '?'.http_build_query($params['p']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $r = json_decode(curl_exec($ch), true);
    echo json_encode($r)."<br />";
    if (!is_array($r))
      throw new \Exception('unexpected response from soundcloud');
    if (isset($r->error))
      throw new \Exception($r->error);
    if (isset($r->errors))
      throw new \Exception(json_encode($r->errors));
    return $r;
  }

}

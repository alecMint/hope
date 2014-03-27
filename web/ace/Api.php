<?php

namespace ace;
use \ace\Ace;

class Api {

  private function __clone(){}
  private function __construct(){}
  private static $params = null;
  private static $routes = array(
    'twitter/oauth2' => 'Twitter::getOAuth2Token',
  );
  
  public static function request($route){
    try {
      self::setData(false,self::_request($route));
    } catch (\Exception $e) {
      self::setData($e->getMessage());
    }
  }

  private static function _request($route){
    $route = Ace::g(self::$routes,$route);
    if (!$route)
      throw new \Exception('route not found');

    $route = explode('::',$route);
    $class = '\\ace\\controllers\\'.$route[0];

    $controller = new $class;
    if (!method_exists($controller,$route[1]))
      throw new \Exception('invalid method');

    return $controller->$methodKey(self::getParams());
  }

  private static function getParams(){
    if (self::$params !== null)
      return self::$params;
    return self::$params = array_merge($_POST,$_GET);
  }

  private static function setData($error,$data){
    $r = $error ? array('error'=>$error) : array('data'=>$data);
    exit(json_encode($r));
  }

}

<?php

namespace ace;
use \ace\Ace;

class Api {

  private function __clone(){}
  private function __construct(){}
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
    $controllerKey = $route[0];
    $methodKey = $route[1];

    $controller = new \ace\controllers\$controllerKey;
    if (!method_exists($controller,$methodKey))
      throw new \Exception('invalid method');

    return $controller->$methodKey(self::getParams());
  }

  private static function getParams(){
    return array_merge($_POST,$_GET);
  }

}

<?php

namespace ace\controllers;

class Ace {

  private function __clone(){}
  private function __construct(){}
  
  public static function request($route){
    var_dump($route);
    exit;
  }

}

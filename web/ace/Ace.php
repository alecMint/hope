<?php

class Ace {

  private static $config = array();

  private function __clone(){}
  private function __construct(){}
  
  public static function loadConfig($F1,$F2){
    echo "loadConfig";exit;
    /*extract(self::$config);
    $args = func_get_args();
    foreach ($args as $a){
      if (is_array($a)) 
        extract($a);
      else if (is_file($a))
        include $a;
    }
    self::$config = get_defined_vars();*/
  }

}

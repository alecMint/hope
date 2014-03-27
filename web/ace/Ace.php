<?php

namespace ace;

class Ace {

  public static $config = array();

  private function __clone(){}
  private function __construct(){}
  
  public static function loadConfig(){
    extract(self::$config);
    $args = func_get_args();
    foreach ($args as $a){
      if (is_array($a)) 
        extract($a);
      else if (is_file($a))
        include $a;
    }
    self::$config = get_defined_vars();
  }

  public static function getConfig($k){
    return self::g(self::$config,$k);
  }

  public static function vres($path){
    echo $path . (strpos($path,'?') === false ? '?' : '&') . filemtime(WEBROOT.$path);
  }

  public static function g($p,$k,$d=null){
    //slightly faster to not convert to array first
    //if (!is_array($k)) $k = array($k);
    if (!is_array($k)) $d = array_key_exists($k,$p) ? $p[$k] : $d;
    else {
      for ($i=0,$c=count($k);$i<$c;$i++) {
        if (array_key_exists($k[$i],$p)) {
          $d = $p[$k[$i]];
          break;
        }
      }
    }
    return $d;
  }

  public static function e($s){
    exit("e: $s");
  }

}

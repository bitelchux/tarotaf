<?php 

// As of PHP 5.2.0, the JSON extension is bundled and compiled into PHP by default.

// (include this file if PHP version is less than 5.2.0)

if(!function_exists("json_decode") or !function_exists("json_encode") ) {
  require_once("libs/pear_json.php");

  $json = new Services_JSON();

  function json_encode($value) {
  	global $json;
    return $json->encode($value);
  }

  function json_decode($input) {
  	global $json;
    return $json->decode($input);
  }
}

?>

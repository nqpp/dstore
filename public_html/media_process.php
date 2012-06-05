<?php
set_include_path(get_include_path().PATH_SEPARATOR.'../application/dev/libraries:../application/dev/config');
@header("Content-Type: text/html; charset=UTF-8");

DEFINE ('BASEPATH', $_SERVER['DOCUMENT_ROOT'].'/');
DEFINE ('URI', $_SERVER['QUERY_STRING']);

$arr = explode('/',URI);
$_uri = array_slice($arr,0,-5);
$args = array_slice($arr,-5);

require('IMagick_Image.php');

$params = array(
  'topDirectory'=>BASEPATH.'images/'.(implode('/',$_uri))
);

try {
  $image = new IMagick_Image($params);
  call_user_func_array(array($image,'get'), $args);
}
catch (Exception $e) {
  print $e->getMessage();
}
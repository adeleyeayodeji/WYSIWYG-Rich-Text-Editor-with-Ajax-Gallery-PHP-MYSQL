<?php
session_start();
//Fix cross Origin
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
//Fix cross Origin
spl_autoload_register("autoclass");

function autoclass($class)
{
  $path = "Class/";
  $classname = $class;
  $extension = ".php";
  $fullpath = $path.$classname.$extension;

  include $fullpath;

}

//Getting IP ADDRESS
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}


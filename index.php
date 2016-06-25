<?php

include 'lib/BARAK.php';
include 'lib/ApplicationModel.php';
include 'lib/ApplicationRoute.php';
include 'lib/ApplicationRoutes.php';
include 'lib/ApplicationException.php';
include 'lib/ApplicationController.php';

ini_set("display_errors", 1); // for message of ApplicationException on html page

date_default_timezone_set('Europe/Istanbul');

// /tmp : create a folder if it doesn't exist

$TEMP = "tmp";

if (!file_exists($TEMP))
  mkdir($TEMP, 0777, true);

// /app/controllers/*.php : files load

$files = glob("app/controllers/*.php");
foreach ($files as $file) include $file;

// /config/database.ini : configuration file load

$CONFIGFILE = "config/database.ini";

if (!file_exists($CONFIGFILE))
  throw new FileNotFoundException("Yapılandırma dosyası mevcut değil", $CONFIGFILE);


$database = parse_ini_file($CONFIGFILE);

// Database connection and model create of tables

$GLOBALS['db'] = new BARAK("mysql:host={$database['host']};dbname={$database['name']}", $database["user"], $database["pass"]);

$table_names = $GLOBALS['db']->table_names();

foreach ($table_names as $table_name) {
  eval("
    class $table_name extends ApplicationModel {
      protected static \$name = '$table_name';
      public function __construct(\$primary_key = false) {
        parent::__construct(\$primary_key);
      }
    }
    ");
}
//echo $_SERVER['REQUEST_URI'];
//$routes->dispatch($_SERVER['REQUEST_URI']);
// $controller = (isset($uri[0]) and $uri[0] != "") ? $uri[0] : "Application";
// $action = isset($uri[1]) ? $uri[1] : "index";

// echo $controller . " ############# " . $action;

// function __autoload($class_name) {
//     require_once 'app/controllers/' . ucwords($class_name) . 'Controller.php';
// }

// /config/routes.php : Router configure file load and run

include 'config/routes.php';
?>
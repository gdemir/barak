<?php

include 'lib/BARAK.php';
include 'lib/ApplicationModel.php';
include 'lib/ApplicationRoutes.php';
include 'lib/ApplicationException.php';
include 'lib/ApplicationController.php';

// Router configure file load

include 'config/routes.php';

// Controller files load

$files = glob("app/controllers/*.php");
foreach ($files as $file) include $file;

// Configuration file load

$CONFIGFILE = "config/database.ini";
try {

  if (!file_exists($CONFIGFILE))

    throw new ApplicationFileNotFoundException("Yapılandırma dosyası mevcut değil : {$CONFIGFILE}");

//  die("Yapılandırma dosyası mevcut değil : {$CONFIGFILE}");
} catch (ApplicationFileNotFoundException $e) {
	// TODO render 404page
  echo "
  <html>
  <head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>404</title>
  </head>
  <body>
    " . $e->getMessage() . "
  </body>
  </html>";
  return;

}


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

$routes->dispatch(trim($_SERVER['REQUEST_URI']));
// $controller = (isset($uri[0]) and $uri[0] != "") ? $uri[0] : "Application";
// $action = isset($uri[1]) ? $uri[1] : "index";

// echo $controller . " ############# " . $action;

// function __autoload($class_name) {
//     require_once 'app/controllers/' . ucwords($class_name) . 'Controller.php';
// }

//$routes->dispatch($uri);
?>
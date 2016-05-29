<?php

include 'lib/barak.php';
include 'lib/ApplicationModel.php';
include 'lib/ApplicationController.php';
$CONFIGFILE = "config/database.ini";

///////////////////////////////////////////////////////////
if (!file_exists($CONFIGFILE))
	die("Yapılandırma dosyası mevcut değil : {$CONFIGFILE}");

$database = parse_ini_file($CONFIGFILE);

$db = new BARAK("mysql:host={$database['host']};dbname={$database['name']}", $database["user"], $database["pass"]);
$table_names = $db->table_names();

foreach ($table_names as $table_name) {
	print_r($table_name);
	eval("
		class $table_name extends ApplicationModel {
			protected static \$name = '$table_name';
		}
	");
}

print_r(Users::key());
print_r(Comments::key());
return;
echo "<br/>";
print_r(Users::first());
echo "<br/>";
print_r(Users::all());
echo "<br/>";
print_r(Comments::fields());
echo "<br/>";
///////////////////////////////////////////////////////////

echo "<br/> request_uri " . $_SERVER['REQUEST_URI'];
// echo "<br/> path_info   " . $_SERVER['PATH_INFO'];
// echo "<br/> query_uri   " . $_SERVER['QUERY_URI'];
echo "<br/> script_name " . $_SERVER['SCRIPT_NAME'];

$uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

print_r($uri);

$controller = isset($uri[0]) ? $uri[0] : "Application";
$action = isset($uri[1]) ? $uri[1] : "index";

echo $controller . " ############# " . $action;

// function __autoload($class_name) {
//     require_once 'app/controllers/' . ucwords($class_name) . 'Controller.php';
// }

$files = glob("app/controllers/*.php");
foreach ($files as $file) include $file;

echo "<br/>";

$class_controller =  ucwords($controller) . 'Controller';
echo $class_controller;

$class = new $class_controller;
// $class->$action();
// echo "<br/>";
// HomeController::index();
?>

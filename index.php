<?php

include 'lib/Database.php';
include 'lib/ApplicationModel.php';
include 'lib/ApplicationController.php';

///////////////////////////////////////////////////////////
$db_name = "test";
Database::connect("localhost", "root", "159654", $db_name);
$table_names = Database::table_names($db_name);

foreach ($table_names as $table_name) {
	eval("
		class $table_name extends ApplicationModel {
			protected static \$name = '$table_name';
		}
	");
}
print_r(Users::first());		
echo "<br/>";
print_r(Users::all());
echo "<br/>";
print_r(Comments::fields());
///////////////////////////////////////////////////////////

echo "<br/> request_uri " . $_SERVER['REQUEST_URI'];
echo "<br/> path_info   " . $_SERVER['PATH_INFO'];
echo "<br/> query_uri   " . $_SERVER['QUERY_URI'];
echo "<br/> script_name " . $_SERVER['SCRIPT_NAME'];

$request_uri = explode("/", $_SERVER['REQUEST_URI']);

print_r($request_uri);

$controller = $request_uri[1];
$action = $request_uri[2];
echo $controller . " " . $action;

function __autoload($class_name) {
    require_once 'app/controllers/' . ucwords($class_name) . 'Controller.php';
}
$files = glob("app/controllers/*.php");

foreach ($files as $file) {
	include $file;
}
echo "<br/>";

$class =  ucwords($controller) . 'Controller';
$class::$action();
HomeController::index();
?>

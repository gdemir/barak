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


// $sonuc = mysql_query("SHOW COLUMNS FROM Users");
// if (!$sonuc) {
//     echo 'Sorguyu çalıştıramadı: ' . mysql_error();
//     exit;
// }
// if (mysql_num_rows($sonuc) > 0) {
//     while ($satir = mysql_fetch_assoc($sonuc)) {
//         print_r($satir);
//     }
// }

echo "<br/>" . $_SERVER['REQUEST_URI'];

?>
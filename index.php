<?php
include 'lib/db.php';
include 'lib/model.php';

$db_name = "test";
DB::connect("localhost", "root", "159654", $db_name);

$table_names = DB::table_names($db_name);

foreach ($table_names as $table_name) {
	eval("
		class $name extends Model {
			protected static \$table_name = $name;
		}
	");
 }

print_r(Users::fields());
print_r(Comments::fields());

echo "<br/>" . $_SERVER['REQUEST_URI'];
?>
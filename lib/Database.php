<?php
class Database {
	public static function connect($_host, $_user, $_pass, $_name) {
		if (!($connector = mysql_connect($_host, $_user, $_pass)))
			die("error : db user or password is wrong<br/>");
		if (!mysql_select_db($_name, $connector))
			die("error : dbname is wrong<br/>");
		mysql_query('set names "utf8"');
		mysql_query('set character set "utf8"'); // dil secenekleri
		mysql_query('set collation_connection = "utf8_general_ci"');
		mysql_query('set collation-server = "utf8_general_ci"');
	}
	public static function table_names($_name) {
		$result = mysql_list_tables($_name);
		for ($table_names = array(); $row = mysql_fetch_assoc($result);)
			$table_names[] = $row["Tables_in_" . $_name];
		return $table_names;
	}
}
?>
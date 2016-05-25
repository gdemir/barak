<?php
class ApplicationModel {

	public static function key() {
		return mysql_fetch_assoc(mysql_query("show index from " . static::$name . " where Key_name = 'PRIMARY'"))["Column_name"];
	}
	public static function find($value) {
		return mysql_query('select * from ' . static::$name . ' where ' . Application::key() . ' = ' . $value);
	}
	// public static function where ($ask) {
	// 	return mysql_query('select * from ' . static::$name . ' ' . $ask);
	// }
	// OK
	public static function fields() {
		$result = mysql_query('select * from ' . static::$name);
		for ($_fields = array(); $field = mysql_fetch_field($result);)
			$_fields[] = $field->name;
		return $_fields;
	}
	public static function first() {
		return mysql_fetch_assoc(mysql_query('select * from ' . static::$name . ' order by ' . static::key() . ' asc'));
	}
	public static function last() {
		return mysql_fetch_assoc(mysql_query('select * from ' . static::$name . ' order by ' . static::key() . ' desc'));
	}
	// OK
	public static function all() {
		$result = mysql_query('select * from ' . static::$name);
		for ($_rows = array(); $row = mysql_fetch_assoc($result);)
			$_rows[] = $row;
		return $_rows;
	}
	private function mysql_to_hash ($query)
	{
		return mysql_fetch_assoc(mysql_query($query));
	}
	// public static function rows($_fields = NULL, $_request = NULL) {
	// 	$_requests = ($_request) ? $_request : '*';
	// 	$_finds = ($_fields) ? ' where ' . $_fields : '';
	// 	$result = mysql_query('select ' . $_requests . ' from ' . static::$name . $_finds);
	// 	$_rows = array(
	// 		'items' => array(),
	// 		'count' => null
	// 		);
	// 	while ($row = mysql_fetch_assoc($result))
	// 		array_push($_rows['items'], $row);
	// 	$result = mysql_fetch_assoc(mysql_query('select count(*) from ' . static::$name . $_finds));
	// 	$_rows['count'] = $result['count(*)'];
	// 	return $_rows;
	// }
	// public static function select($_units) {
	// 	return mysql_fetch_assoc(mysql_query('select * from ' . static::$name . ' where '. $_units));
	// }
	// public static function erase($_units) {
	// 	return mysql_query('delete from ' . static::$name . ' where '. $_units);
	// }
	// public static function save($_fields, $_values) {
	// 	if (!(is_null($_fields) && is_null($_fields) && is_null($_values)))
	// 		mysql_query(
	// 			'insert into ' . static::$name .
	// 			          ' (' . $_fields . ') ' .
	// 			     'values(' . $_values . ')'
	// 		);
	// }
	// public static function update($_sets, $_keys) {
	// 	if (!(is_null($_sets) && is_null($_keys)))
	// 		mysql_query(
	// 			'update ' . static::$name .
	// 			  ' set ' . $_sets .
	// 			' where ' . $_keys
	// 		);
	// }
}
?>

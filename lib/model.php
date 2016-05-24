<?php
class ApplicationModel {
	public static function key($_table) {
		return mysql_fetch_field(mysql_query('select * from '. $_table))->name;
	}
	public static function query($ask) {
		return mysql_query($ask);
	}
	public static function fields() {
		$result = mysql_query('select * from ' . static::$table_name);
		$_fields = array();
		while ($field = mysql_fetch_field($result))
			array_push($_fields, $field->name);
		return $_fields;
	}
	public static function rows($_fields = NULL, $_request = NULL) {
		$_requests = ($_request) ? $_request : '*';
		$_finds = ($_fields) ? ' where ' . $_fields : '';
		$result = mysql_query('select ' . $_requests . ' from ' . static::$table_name . $_finds);
		$_rows = array(
			'items' => array(),
			'count' => null
			);
		while ($row = mysql_fetch_assoc($result))
			array_push($_rows['items'], $row);
		$result = mysql_fetch_assoc(mysql_query('select count(*) from ' . static::$table_name . $_finds));
		$_rows['count'] = $result['count(*)'];
		return $_rows;
	}
	public static function select($_units) {
		return mysql_fetch_assoc(mysql_query('select * from ' . static::$table_name . ' where '. $_units));
	}
	public static function erase($_units) {
		return mysql_query('delete from ' . static::$table_name . ' where '. $_units);
	}
	public static function save($_fields, $_values) {
		if (!(is_null($_fields) && is_null($_fields) && is_null($_values)))
			mysql_query(
				'insert into ' . static::$table_name .
				          ' (' . $_fields . ') ' .
				     'values(' . $_values . ')'
			);
	}
	public static function update($_sets, $_keys) {
		if (!(is_null($_sets) && is_null($_keys)))
			mysql_query(
				'update ' . static::$table_name .
				  ' set ' . $_sets .
				' where ' . $_keys
			);
	}
}
?>

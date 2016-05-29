<?php
class ApplicationModel {
  public static function primary_key () {
    return $GLOBALS['db']->query("show index from " . static::$name . " where Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
  }
  public static function find ($primary_key) {
    return $GLOBALS['db']->query("select * from " . static::$name . " where " . self::primary_key() . " = " . $primary_key)->fetch(PDO::FETCH_ASSOC);
  }
  // // public static function where ($ask) {
  // //   return mysql_query('select * from ' . static::$name . ' ' . $ask);
  // // }
  // // OK
  // public static function fields() {
  //  $result = mysql_query('select * from ' . static::$name);
  //  for ($_fields = array(); $field = mysql_fetch_field($result);)
  //    $_fields[] = $field->name;
  //  return $_fields;
  // }
  public static function first () {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " asc")->fetch(PDO::FETCH_ASSOC);
  }
  public static function last () {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " desc")->fetch(PDO::FETCH_ASSOC);
  }
  public static function all () {
    $result = $GLOBALS['db']->query('select * from ' . static::$name);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) $rows[] = $row;
    return $rows;
  }
  // public static function select($_units) {
  //  return mysql_fetch_assoc(mysql_query('select * from ' . static::$name . ' where '. $_units));
  // }
  // public static function erase($_units) {
  //  return mysql_query('delete from ' . static::$name . ' where '. $_units);
  // }
  // public static function save($_fields, $_values) {
  //  if (!(is_null($_fields) && is_null($_fields) && is_null($_values)))
  //    mysql_query(
  //      'insert into ' . static::$name .
  //                ' (' . $_fields . ') ' .
  //           'values(' . $_values . ')'
  //    );
  // }
  // public static function update($_sets, $_keys) {
  //  if (!(is_null($_sets) && is_null($_keys)))
  //    mysql_query(
  //      'update ' . static::$name .
  //        ' set ' . $_sets .
  //      ' where ' . $_keys
  //    );
  // }
}
?>
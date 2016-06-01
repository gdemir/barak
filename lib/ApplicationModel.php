<?php
class ApplicationModel {

  private $fields;

  public function __construct () {
    $this->fields = (object)$this->fieldnames();
  }

  public function __get ($field) {
    if (isset($this->fields->$field))
      return $this->fields->$field;
    else
      die("Tabloda böyle bir $name key (anahtar) mevcut değil<br/>");
  }

  public function __set ($field, $value) {
     if (in_array($field, array_keys((array)$this->fields)))
      $this->fields->$field = $value;
     else
      die("Tabloda böyle bir $name $value key/value için key mevcut değil<br/>");
  }

  // Static functions

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

  public static function fieldnames () {
    return $GLOBALS['db']->query('describe ' . static::$name)->fetchAll(PDO::FETCH_COLUMN);
  }

  public static function first () {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " asc")->fetch(PDO::FETCH_ASSOC);
  }

  public static function last () {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " desc")->fetch(PDO::FETCH_ASSOC);
  }

  public static function all () {
    return $GLOBALS['db']->query('select * from ' . static::$name)->fetchAll(PDO::FETCH_ASSOC);
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

  public static function update ($primary_key, $conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();
    foreach ($fields as $field)
      if (!in_array($field, $table_fields))
        die("bilinmeyen sütun adı" . $field); #TODO must notice units or class

    $sets = '';
    foreach ($conditions as $field => $value)
      $sets .= ($sets ? ',' : '') . ($field . '="' . $value .'"');
 
    $GLOBALS['db']->query(
      "update " . static::$name .
      " set " . $sets .
      " where " . self::primary_key() . "=" . $primary_key
    );
  }
}
?>

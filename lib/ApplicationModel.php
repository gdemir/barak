<?php
class ApplicationModel {

  private $_fields;
  private $_new_record_state;

  public function __construct($conditions = false, $new_record_state = true) {
  	$this->_new_record_state = $new_record_state;
    if ($conditions) {
      $this->_fields = $this->check_fieldnames($conditions);
    } else {
      $this->_fields = $this->draft_fieldnames();
    }
  }

// Public Functions

  public function save() {

    if (!$this->_new_record_state) {

      $primary_keyname = self::primary_keyname();

      $sets = self::condition_to_sql_statement($this->_fields, ",");
      $key =  $primary_keyname . "='" . $this->_fields[$primary_keyname] . "'";

      $GLOBALS['db']->query(
        "update " . static::$name .
        " set " . $sets .
        " where " . $key
        );

    } else {

      $fields = "";
      $values = "";
      $primary_keyname = self::primary_keyname();
      foreach ($this->_fields as $field => $value) {
        if ($primary_keyname != $field or ($primary_keyname == $field and !empty($value))) {
          $fields .= ($fields ? "," : "") . $field;
          $values .= ($values ? "," : "") . "'". $value . "'";
        }
      }

      $GLOBALS["db"]->query(
        "insert into " . static::$name .
        " (" . $fields . ") " .
        "values(" . $values . ")"
        );

    }
  }

  public function __get($field) {
    if (isset($this->_fields[$field]))
      return $this->_fields[$field];
    else
      die("Tabloda böyle bir $field key (anahtar) mevcut değil<br/>");
  }

  public function __set($field, $value) {
    if (in_array($field, array_keys($this->_fields)))
      $this->_fields[$field] = $value;
    else
      die("Tabloda böyle bir $field $value key/value için key mevcut değil<br/>");
  }

  // Private Functions

  private function check_fieldnames($conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();

    $checked_conditions = [];

    foreach ($fields as $field)
      if (in_array($field, $table_fields))
        $checked_conditions[$field] = $conditions[$field];

    return $checked_conditions;
  }

  private function draft_fieldnames() {
    $result = $GLOBALS['db']->query("describe " . static::$name);
    while ($field = $result->fetch(PDO::FETCH_COLUMN)) $fields[$field] = "";
    return $fields;
  }

  private function check_field($field) {
    return in_array($field, self::fieldnames()) ? true : false;
  }

  private function condition_to_sql_statement($conditions, $delimiter = ",") {
    $sets = "";
    foreach ($conditions as $field => $value)
      $sets .= ($sets ? " $delimiter " : "") . ($field . "='" . $value . "'");
    return $sets;
  }

  // Public Static Functions

  public static function primary_keyname() {
    return $GLOBALS['db']->query("show index from " . static::$name . " where Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
  }

  // query primary_key
  //
  // $user = User::find(1); // return User objects
  // $user->first_name = 'foo';
  // $user->save();

  public static function find($primary_key) {
    if ($record = $GLOBALS['db']->query("select * from " . static::$name . " where " . self::primary_keyname() . " = " . $primary_key)->fetch(PDO::FETCH_ASSOC))
      return new static::$name($record, false);
    return null;
  }

  // $users = User::find_all([1, 2, 3]); // return User objects array
  //
  // foreach ($users as $user) {
  //   $user->first_name = "Gökhan";
  //   $user->save();
  // }

  public static function find_all($primary_keys) {
  	foreach ($primary_keys as $primary_key) {
  		$objects[] = static::$name::find($primary_key);
  	}
    return isset($objects) ? $objects : null;
  }

  public static function where($conditions = null) {
    $sets = ($conditions) ? " where " . self::condition_to_sql_statement($conditions, "and") : "";
    $records = $GLOBALS['db']->query("select * from " . static::$name . $sets)->fetchAll(PDO::FETCH_ASSOC);

    if ($records) {
    	$primary_keyname = self::primary_keyname();

      foreach ($records as $record) {
        $primary_key = (int)$record[$primary_keyname];
        $objects[] = static::$name::find($primary_key);
      }
      return isset($objects) ? $objects : null;
    }
    return null;
  }

  public static function all() {
    return self::where(null);
  }

  // public static function joins($tables, $conditions) {
  // 	$table_u = ucfirst($table);
  // 	echo "
  // 	    select * from " . static::$name . "," . $table_u .
  //   	" where " .
  //   	static::$name . "." . $table . "_" . $table_u::primary_keyname() . " = " . $table_u . "." . $table_u::primary_keyname();

  //   $a = $GLOBALS['db']->query("
  //   	select * from " . static::$name . "," . $table_u .
  //   	" where " .
  //   	static::$name . "." . $table . "_" . $table_u::primary_keyname() . " = " . $table_u . "." . $table_u::primary_keyname()
  //  	)->fetchAll(PDO::FETCH_ASSOC);
  //  	print_r($a);

  // }
  public static function exists($primary_key) {
    return ($GLOBALS['db']->query("select * from " . static::$name . " where " . self::primary_keyname() . " = " . $primary_key)->fetch(PDO::FETCH_ASSOC)) ? TRUE : false;
  }

  public static function fieldnames() {
    return $GLOBALS['db']->query("describe " . static::$name)->fetchAll(PDO::FETCH_COLUMN);
  }

  public static function first($limit = 1) {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_keyname() . " asc limit " . $limit)->fetch(PDO::FETCH_ASSOC);
  }

  public static function last($limit = 1) {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_keyname() . " desc limit " . $limit)->fetch(PDO::FETCH_ASSOC);
  }

  public static function order($field, $sort_type = "asc") {
    if (self::check_field($field))
      return $GLOBALS['db']->query("select * from " . static::$name . " order by " . $field . " " . $sort_type)->fetchAll(PDO::FETCH_ASSOC);
    die("Tabloda böyle bir $field key (anahtar) mevcut değil<br/>");
  }

  public static function update($primary_key, $conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();

    foreach ($fields as $field)
      if (!in_array($field, $table_fields))
        die("Bilinmeyen Sütun Adı" . $field); #TODO must notice units or class

    $sets = self::condition_to_sql_statement($conditions, ",");

    $GLOBALS['db']->query(
      "update " . static::$name .
      " set " . $sets .
      " where " . self::primary_keyname() . "=" . $primary_key
    );
  }

  public static function delete($primary_key) {
    $GLOBALS['db']->query("delete from " . static::$name . " where " . self::primary_keyname() . " = " . $primary_key);
  }

  public static function delete_all($conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();

    foreach ($fields as $field)
      if (!in_array($field, $table_fields))
          die("Bilinmeyen Sütun Adı" . $field); #TODO must notice units or class

    $sets = self::condition_to_sql_statement($conditions, "and");

    $GLOBALS['db']->query("delete from " . static::$name . " where " . $sets);
  }

}
?>

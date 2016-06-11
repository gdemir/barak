<?php
class ApplicationModel {

  private $fields;
  private $new_record_state;

  public function __construct($conditions = false) {
    if ($conditions) {
      $this->new_record_state = false;
      $this->fields = $this->load_fieldnames($conditions);
    } else {
      $this->new_record_state = true;
      $this->fields = $this->draft_fieldnames();
    }
  }

// Public Functions

  public function save() {

    if (!$this->new_record_state) {

      $sets = "";
      $keys = "";
      $primary_keyname = self::primary_keyname();
      foreach ($this->fields as $field => $value)
        $sets .= ($sets ? "," : "") . ($field . "='" . $value . "'");
      $key =  $primary_keyname . "='" . $this->fields[$primary_keyname] . "'";

      $GLOBALS['db']->query(
        "update " . static::$name .
        " set " . $sets .
        " where " . $key
        );

    } else {

      $fields = "";
      $values = "";
      $primary_keyname = self::primary_keyname();
      foreach ($this->fields as $field => $value) {
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
    if (isset($this->fields[$field]))
      return $this->fields[$field];
    else
      die("Tabloda böyle bir $field key (anahtar) mevcut değil<br/>");
  }

  public function __set($field, $value) {
    if (in_array($field, array_keys($this->fields)))
      $this->fields[$field] = $value;
    else
      die("Tabloda böyle bir $field $value key/value için key mevcut değil<br/>");
  }

  // Private Functions

  private function load_fieldnames($conditions) {
    return $GLOBALS['db']->query("select * from " . static::$name . " where " . $conditions)->fetch(PDO::FETCH_ASSOC);
  }

  private function draft_fieldnames() {
    $result = $GLOBALS['db']->query("describe " . static::$name);
    while ($field = $result->fetch(PDO::FETCH_COLUMN)) $fields[$field] = "";
    return $fields;
  }

  private function check_field($field) {
    return in_array($field, self::fieldnames()) ? true : false;
  }

  // Public Static Functions

  public static function delete($primary_key) {
    $GLOBALS['db']->query("delete from " . static::$name . " where " . self::primary_keyname() . " = " . $primary_key);
  }

  public static function delete_all($conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();

    foreach ($fields as $field)
      if (!in_array($field, $table_fields))
          die("Bilinmeyen Sütun Adı" . $field); #TODO must notice units or class

    $sets = "";
    foreach ($conditions as $field => $value)
      $sets .= ($sets ? "," : "") . ($field . "='" . $value . "'");

    $GLOBALS['db']->query("delete from " . static::$name . " where " . $sets);
  }

  public static function primary_keyname() {
    return $GLOBALS['db']->query("show index from " . static::$name . " where Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
  }

  public static function find_all($conditions) {

  }
  // query primary_key or primary_keys
  //
  // $user = Users::find(1); // return Users objects
  // $user->first_name = 'foo';
  // $user->save();

  // $users = Users::find([1, 2, 3]); // return Users objects array

  public static function find($primary_keys) {
    $class = static::$name;
    $condition = self::primary_keyname() . " = " . $primary_key;

    if (is_array($primary_keys)) {
      $class = static::$name;
      foreach ($primary_keys as $primary_key)
        if (self::exists($primary_key))
          $objects[] = new $class($condition);
        return isset($objects) ? $objects : null;
    } elseif (is_int($primary_keys)) {
      if (self::exists($primary_keys))
        return new $class($condition);
    }
    return null;
  }

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

  public static function all() {
		$primary_keyname = self::primary_keyname();

    $records = $GLOBALS['db']->query('select * from ' . static::$name)->fetchAll(PDO::FETCH_ASSOC);

    if ($records) {
      $class = static::$name;
      foreach ($records as $record)
        $objects[] = new $class($primary_keyname . " = " . $record[$primary_keyname]);
      return isset($objects) ? $objects : null;
    } else
      return null;
  }

  public static function update($primary_key, $conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();
    foreach ($fields as $field)
      if (!in_array($field, $table_fields))
        die("Bilinmeyen Sütun Adı" . $field); #TODO must notice units or class

    $sets = "";
    foreach ($conditions as $field => $value)
      $sets .= ($sets ? "," : "") . ($field . "='" . $value . "'");

    $GLOBALS['db']->query(
      "update " . static::$name .
      " set " . $sets .
      " where " . self::primary_keyname() . "=" . $primary_key
    );
  }
}
?>

<?php
class ApplicationModel {

  private $fields;
  private $new_record_state;

  public function __construct($primary_key = false) {
    if ($primary_key) {
      $this->new_record_state = false;
      $this->fields = $this->load_fieldnames($primary_key);
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
      $primary_key = self::primary_key();
      foreach ($this->fields as $field => $value)
        $sets .= ($sets ? "," : "") . ($field . "='" . $value . "'");
      $key =  $primary_key . "='" . $this->fields[$primary_key] . "'";

      $GLOBALS['db']->query(
        "update " . static::$name .
        " set " . $sets .
        " where " . $key
        );

    } else {

      $fields = "";
      $values = "";
      $primary_key = self::primary_key();
      foreach ($this->fields as $field => $value) {
        if ($primary_key != $field or ($primary_key == $field and !empty($value))) {
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

  private function load_fieldnames($ask) {
    return $GLOBALS['db']->query("select * from " . static::$name . " where " . $ask)->fetch(PDO::FETCH_ASSOC);
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

  public static function primary_key() {
    return $GLOBALS['db']->query("show index from " . static::$name . " where Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
  }

  // return object or objects
  //
  // $user = Users::find(1);
  // $user->first_name = 'foo';
  // $user->save();

  public static function find($primary_keys) {
		$class = static::$name;
		$ask = self::primary_key() . " = " . $primary_key;

    if (is_array($primary_keys)) {
    	$class = static::$name;
      foreach ($primary_keys as $primary_key)
        if (self::exists($primary_key))
          $objects[] = new $class($ask);
      return isset($objects) ? $objects : null;
    } elseif (is_int($primary_keys)) {
        if (self::exists($primary_keys))
          return new $class($ask);
    }
    return null;
  }

  public static function exists($primary_key) {
    return ($GLOBALS['db']->query("select * from " . static::$name . " where " . self::primary_key() . " = " . $primary_key)->fetch(PDO::FETCH_ASSOC)) ? TRUE : false;
  }

  public static function where($ask) {
  	// $class = static::$name;
  	// $result = $GLOBALS['db']->query("select * from " . static::$name . " where " . $ask);

  	// while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			// $objects[] = new $class($ask);
  	// }
   //  return isset($objects) ? $objects : null;

    //return $GLOBALS['db']->query("select * from " . static::$name . " where " . $ask)->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function fieldnames() {
    return $GLOBALS['db']->query("describe " . static::$name)->fetchAll(PDO::FETCH_COLUMN);
  }

  public static function first() {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " asc limit 1")->fetch(PDO::FETCH_ASSOC);
  }

  public static function last() {
    return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " desc limit 1")->fetch(PDO::FETCH_ASSOC);
  }

  public static function order($field, $sort_type = "asc") {
    if (self::check_field($field))
      return $GLOBALS['db']->query("select * from " . static::$name . " order by " . $field . " " . $sort_type)->fetchAll(PDO::FETCH_ASSOC);
    die("Tabloda böyle bir $field key (anahtar) mevcut değil<br/>");
  }

  public static function all() {
    return $GLOBALS['db']->query('select * from ' . static::$name)->fetchAll(PDO::FETCH_ASSOC);
  }

  // public static function select($_units) {
  //  return mysql_fetch_assoc(mysql_query('select * from ' . static::$name . ' where '. $_units));
  // }
  // public static function erase($_units) {
  //  return mysql_query('delete from ' . static::$name . ' where '. $_units);
  // }

  public static function update($primary_key, $conditions) {
    $fields = array_keys($conditions);
    $table_fields = self::fieldnames();
    foreach ($fields as $field)
      if (!in_array($field, $table_fields))
          die("bilinmeyen sütun adı" . $field); #TODO must notice units or class

        $sets = "";
        foreach ($conditions as $field => $value)
          $sets .= ($sets ? "," : "") . ($field . "='" . $value . "'");

        $GLOBALS['db']->query(
          "update " . static::$name .
          " set " . $sets .
          " where " . self::primary_key() . "=" . $primary_key
          );
      }
    }
    ?>

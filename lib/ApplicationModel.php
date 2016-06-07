<?php
class ApplicationModel {

  private $fields;
  private $new_record_state;

  public function __construct() {
    $this->new_record_state = true;
    $this->fields = $this->draft_fieldnames();
  }

// Public Functions

  public function save() {

    if (!$this->new_record_state) {
      $sets = "";
      $keys = "";
      $primary_key = self::primary_key();
      foreach ($this->fields as $field => $value)
        if ($primary_key == $field)
          $keys .= ($keys ? "," : "") . ($field . "='" . $value . "'");
        else
          $sets .= ($sets ? "," : "") . ($field . "='" . $value . "'");

      $GLOBALS['db']->query(
        "update " . static::$name .
        " set " . $sets .
        " where " . $keys
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

    private function draft_fieldnames() {
      $result = $GLOBALS['db']->query("describe " . static::$name);
      while ($field = $result->fetch(PDO::FETCH_COLUMN)) $a[$field] = "";
      return $a;
    }

  // Public Static Functions

    public static function primary_key() {
      return $GLOBALS['db']->query("show index from " . static::$name . " where Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
    }

    public static function find($primary_key) {
      return $GLOBALS['db']->query("select * from " . static::$name . " where " . self::primary_key() . " = " . $primary_key)->fetch(PDO::FETCH_ASSOC);
    }

    public static function where($ask) {
      return $GLOBALS['db']->query("select * from " . static::$name . " " . $ask)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fieldnames() {
      return $GLOBALS['db']->query("describe " . static::$name)->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function first() {
      return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " asc")->fetch(PDO::FETCH_ASSOC);
    }

    public static function last() {
      return $GLOBALS['db']->query("select * from " . static::$name . " order by " . self::primary_key() . " desc")->fetch(PDO::FETCH_ASSOC);
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

      $sets = '';
      foreach ($conditions as $field => $value)
        $sets .= ($sets ? ',' : '') . ($field . '="' . $value .'"');

      $GLOBALS['db']->query(
        'update ' . static::$name .
        ' set ' . $sets .
        ' where ' . self::primary_key() . '=' . $primary_key
        );
    }
  }
  ?>

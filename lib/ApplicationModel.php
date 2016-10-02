<?php

class ApplicationModel {

  private $_select;
  private $_table;
  private $_where;
  private $_group;
  private $_limit;
  private $_order;

  private $_fields;
  private $_new_record_state;

  // System Main Functions

  public function __construct($conditions = false, $new_record_state = true) {
    $this->_new_record_state = $new_record_state;
    if ($conditions) {
      $this->check_fieldnames(array_keys($conditions));
      $this->_fields = $conditions;
    } else {
      $this->_fields = $this->draft_fieldnames();
    }
  }

  public static function load() {
    $object = new static::$name();
    $object->_table = static::$name;
    return $object;
  }

  public function get() {

    $record = $GLOBALS['db']->query(
      "select " . ($this->_select ? implode(",", array_flip($this->_select)) : "*") .
      " from " . $this->_table .
      ($this->_where ? " where " . self::implode_key_and_value($this->_where, "and") : "") .
      ($this->_order ? " order by " . $this->_order : "") .
      ($this->_group ? " group by " . $this->_group : "") .
      ($this->_limit ? " limit " . $this->_limit : "")
      )->fetch(PDO::FETCH_ASSOC);

    return $record ? $record : null;
  }

  public function get_all() {

    $records = $GLOBALS['db']->query(
      "select " . ($this->_select ? implode(",", array_flip($this->_select)) : "*") .
      " from " . $this->_table .
      ($this->_where ? " where " . self::implode_key_and_value($this->_where, "and") : "") .
      ($this->_order ? " order by " . $this->_order : "") .
      ($this->_group ? " group by " . $this->_group : "") .
      ($this->_limit ? " limit " . $this->_limit : "")
      )->fetchAll(PDO::FETCH_ASSOC);

    if ($records) {
      foreach ($records as $record)
        $objects[] = static::$name::find($record["id"]);
        //$objects[] = static::$name::load()->where(["id" => $record["id"]])->select(($this->_select ? implode(",", array_flip($this->_select)) : "*"));
      return isset($objects) ? $objects : null;
    }
    return null;
  }

  public function __get($field) {
    if (isset($this->_fields[$field]))
      return $this->_fields[$field];
    else
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $field);
  }

  public function __set($field, $value) {
    if (in_array($field, array_keys($this->_fields)))
      $this->_fields[$field] = $value;
    else
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $field);
  }

// Public Functions

  public function save() {

    if (!$this->_new_record_state) {

      $sets = self::implode_key_and_value($this->_fields, ",");
      $key =  "id = '" . $this->_fields["id"] . "'";

      ApplicationSql::update(static::$name, $sets, $key);

    } else {

      $fields = "";
      $values = "";
      foreach ($this->_fields as $field => $value) {
        if ("id" != $field or ("id" == $field and !empty($value))) {
          $fields .= ($fields ? "," : "") . $field;
          $values .= ($values ? "," : "") . "'". $value . "'";
        }
      }

      ApplicationSql::create(static::$name, $fields, $values);
    }
  }

  // ok
  public function select($fields) {
    $fields = self::check_table_and_field(array_flip(explode(",", $fields)));

    $this->_select = $fields;
    return $this;
  }


  // ok
  public function where($conditions = null) {
    $conditions = self::check_table_and_field($conditions);

    // all value change to string like $value -> "$value"
    array_walk($conditions, function(&$value, $key) { $value = '"' . $value . '"'; });

    $this->_where = ($this->_where) ? array_merge($this->_where, $conditions) : $conditions;
    return $this;
  }

  public function joins($tables) {

    $table_belong_array = [];
    foreach ($tables as $table) {
      $table_belongs = preg_grep("/(.*)_id/", ApplicationSql::fieldnames(static::$name));
      foreach ($table_belongs as $table_belong)
        $table_belong_array[$table . "." . $table_belong] = ucfirst(str_replace("_", ".", $table_belong));
    }

    $this->_table = $this->_table . "," . implode(",", $tables);
    $this->_where = ($this->_where) ? array_merge($this->_where, $table_belong_array) : $table_belong_array;

    return $this;
  }

  public function group($field = null) {
    self::check_fieldname($field);
    $this->_group = $field;
    return $this;
  }

  // ok
  public function limit($count = null) {
    $this->_limit = $count;
    return $this;
  }

  // ok
  public function order($field, $sort_type = "asc") {
    self::check_fieldname($field);
    $this->_order = $field . " " . $sort_type;
    return $this;
  }

  // ok
  public function first($limit = 1) {
    $this->_order = "id asc limit " . $limit;
    return $this;
  }

  // ok
  public function last($limit = 1) {
    $this->_order = "id desc limit " . $limit;
    return $this;
  }

  // Private Functions

  private function check_table_and_field($conditions) {
    foreach ($conditions as $field => $value) {
      if (strpos($field, '.') !== false) {
        list($request_table, $request_field) = explode('.', $field);

        self::check_tablename(trim($request_table));
        self::check_fieldname(trim($request_field), trim($request_table));
      } else {
        $conditions[$this->_table . '.' .  $field] = "'" . $value . "'";
        unset($conditions[$field]);
      }
    }
    return $conditions;
  }

  // ok
  private function check_tablename($table) {
    if (!in_array($table, ApplicationSql::tablenames()))
      throw new TableNotFoundException("Veritabanında böyle bir tablo mevcut değil", $table);
  }

  private function check_tablenames($tables) {
    $database_tables = ApplicationSql::tablenames();
    foreach ($tables as $table) {
      if (!in_array($table, $database_tables))
        throw new TableNotFoundException("Veritabanında böyle bir tablo mevcut değil", $table);
    }
  }

  private function check_fieldname($field, $table = null) {
    $table = $table ?? static::$name;
    if (!in_array($field, ApplicationSql::fieldnames($table)))
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $table . "." . $field);
  }

  private function check_fieldnames($fields, $table = null) {
    $table = $table ?? static::$name;
    $table_fields = ApplicationSql::fieldnames($table);
    foreach ($fields as $field) {
      if (!in_array($field, $table_fields))
        throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $table . "." . $field);
    }
  }

  private function draft_fieldnames() {
    $result = ApplicationSql::describe(static::$name);
    while ($field = $result->fetch(PDO::FETCH_COLUMN)) $fields[$field] = "";
    return $fields;
  }

  private function field_exists($field) {
    return in_array($field, ApplicationSql::fieldnames(static::$name)) ? true : false;
  }

  // ok
  private function implode_key_and_value($conditions, $delimiter = ",") {
    return implode(" $delimiter ", array_map(function ($key, $value) { return $key . "=" . $value; }, array_keys($conditions), $conditions));
  }

  // Public Static Functions

  // echo User::primary_keyname();

  // ok
  public static function primary_keyname() {
    return ApplicationSql::primary_keyname(static::$name);
  }

  // query primary_key
  //
  // $user = User::find(1); // return User objects
  // $user->first_name = 'foo';
  // $user->save();

  public static function find($primary_key) {
    if ($record = ApplicationSql::read(static::$name, "id = " . $primary_key)->fetch(PDO::FETCH_ASSOC))
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
    foreach ($primary_keys as $primary_key)
      $objects[] = static::$name::find($primary_key);
    return isset($objects) ? $objects : null;
  }

  // ok
  public static function all() {
    return static::$name::load()->get_all();
  }

  public static function exists($primary_key) {
    return static::$name::find($primary_key) ? true : false;
  }

  // ok
  public static function update($primary_key, $conditions) {
    self::check_fieldnames(array_keys($conditions));

    $sets = self::implode_key_and_value($conditions, ",");
    ApplicationSql::update(static::$name, $sets, "id = " . $primary_key);
  }

  public static function delete($primary_key) {
    ApplicationSql::delete(static::$name, "id = " . $primary_key);
  }

  public static function delete_all($conditions) {
    self::check_fieldnames(array_keys($conditions));

    $sets = self::implode_key_and_value($conditions, "and");
    ApplicationSql::delete(static::$name, $sets);
  }
}
?>
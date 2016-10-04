<?php
//
class ApplicationModel {

  private $_select;
  private $_table;
  private $_where;
  private $_group;
  private $_limit;
  private $_order;

  private $_fields;
  private $_new_record_state;

  //////////////////////////////////////////////////
  // System Main Functions
  //////////////////////////////////////////////////

  // Ekleme ör.: 1

  // $user = new User();
  // $user->first_name ="Gökhan";
  // $user->last_name ="Demir";
  // $user->save(); // yeni kayıt eklendi

  // Ekleme ör.: 2

  // $user = new User(["last_name" => "Demir"]);
  // $user->first_name ="Gökhan";
  // $user->save(); // yeni kayıt eklendi

  // Günceleme ör.: 1

  // $this->users = User::all(); // tüm users içinde id olduğu için kayıtları güncelleme özelliği oluyor.
  // foreach ($this->users as user) {
  //   user.first_name = "Gökhan";
  //   user.save();
  // }

  public function __construct($conditions = null) {
    $this->_new_record_state = true;

    foreach (ApplicationSql::fieldnames(static::$name) as $fieldname) {
      if ($conditions) {

          // simple load for create
        $this->_fields[$fieldname] = in_array($fieldname, array_keys($conditions)) ? $conditions[$fieldname] : "";

      } else {

          // create draft fieldnames
        $this->_fields[$fieldname] = "";

      }
    }

  }

  public static function load() {
    $object = new static::$name();
    $object->_table = static::$name;
    return $object;
  }

  // ok
  public function get() {

    echo "select " . ($this->_select ? implode(",", $this->_select) : "*") .
    " from " . $this->_table .
    ($this->_where ? " where " . self::implode_key_and_value($this->_where, "and") : "") .
    ($this->_order ? " order by " . $this->_order : "") .
    ($this->_group ? " group by " . $this->_group : "") .
    ($this->_limit ? " limit " . $this->_limit : "");

    $record = $GLOBALS['db']->query(
      "select " . ($this->_select ? implode(",", $this->_select) : "*") .
      " from " . $this->_table .
      ($this->_where ? " where " . self::implode_key_and_value($this->_where, "and") : "") .
      ($this->_order ? " order by " . $this->_order : "") .
      ($this->_group ? " group by " . $this->_group : "") .
      ($this->_limit ? " limit " . $this->_limit : "")
      )->fetch(PDO::FETCH_ASSOC);

    print_r($record);

    return $record ? $record : null;
  }

  // ok
  public function get_all() {

    echo "select " . ($this->_select ? implode(",", $this->_select) : "*") .
    " from " . $this->_table .
    ($this->_where ? " where " . self::implode_key_and_value($this->_where, "and") : "") .
    ($this->_order ? " order by " . $this->_order : "") .
    ($this->_group ? " group by " . $this->_group : "") .
    ($this->_limit ? " limit " . $this->_limit : "");
    $records = $GLOBALS['db']->query(
      "select " . ($this->_select ? implode(",", $this->_select) : "*") .
      " from " . $this->_table .
      ($this->_where ? " where " . self::implode_key_and_value($this->_where, "and") : "") .
      ($this->_order ? " order by " . $this->_order : "") .
      ($this->_group ? " group by " . $this->_group : "") .
      ($this->_limit ? " limit " . $this->_limit : "")
      )->fetchAll(PDO::FETCH_ASSOC);
    echo "<br/>";echo "<br/>";echo "<br/>";
    print_r($records);

    if ($records) {
      foreach ($records as $record)
        $objects[] = static::$name::find($record["id"]);
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

  //////////////////////////////////////////////////
  // Public Functions
  //////////////////////////////////////////////////

  // $user = new User();
  // $user->first_name ="Gökhan";
  // $user->save(); // kayıt ettikten sonra otomatik id değeri alır.

  // print_r($user);
  // [_fields:ApplicationModel:private] => Array ( [first_name] => Gökhan [last_name] => [username] => [password] => [content] => [id] => 368 )

  // ok
  public function save() {

    if (!$this->_new_record_state) {

      self::check_id();

      $sets = self::implode_key_and_value($this->_fields, ",");
      $primary_key = $this->_fields["id"];

      ApplicationSql::update(static::$name, $sets, "id = '" . $primary_key . "'");

    } else {

      $fields = "";
      $values = "";
      foreach ($this->_fields as $field => $value) {
        if ("id" != $field or ("id" == $field and !empty($value))) {
          $fields .= ($fields ? "," : "") . $field;
          $values .= ($values ? "," : "") . "'". $value . "'";
        }
      }

      // kayıt tamamlandıktan sonra en son id döndür
      $primary_key = ApplicationSql::create(static::$name, $fields, $values);

      // artık yeni kayıt değil
      $this->_new_record_state = false;

      // id'si olan kaydın alan değerlerini yeni kayıtta güncelle, (ör.: id otomatik olarak alması için)
      $this->_fields = static::$name::find($primary_key)->_fields;
    }
  }

  // ok
  public function select($fields) {
    $array_fields = explode(",", $fields);
    $fields = self::check_table_and_field(array_combine($array_fields, $array_fields));

    // varsayılan olarak ekle, objeler yüklenirken her zaman id olmalıdır.
    $table_and_primary_key = static::$name . ".id";

    $fields[$table_and_primary_key] = $table_and_primary_key;

    $this->_select = $fields;
    return $this;
  }

  // ok
  public function where($conditions = null) {
    $conditions = self::check_table_and_field($conditions);

    // all value change to string like $value -> "$value"
    //array_walk($conditions, function(&$value, $key) { $value = '"' . $value . '"'; });
    array_walk($conditions, function(&$value, $key) { $value = '"' . $value . '"'; });

    $this->_where = ($this->_where) ? array_merge($this->_where, $conditions) : $conditions;
    return $this;
  }

  // ok
  public function joins($tables) {

    $table_belong_array = [];
    foreach ($tables as $table) {
      $table_fieldnames = ApplicationSql::fieldnames($table);
      $table_belongs = preg_grep("/(.*)_id/", $table_fieldnames);

      // join işlemi için User.id = Comment.user_id gibi where'ye eklemeler yap
      foreach ($table_belongs as $table_belong)
        $this->_where[$table . "." . $table_belong] = ucfirst(str_replace("_", ".", $table_belong));

      // join işleminde select çakışması önlenmesi için User.first_name, User.last_name gibi ekleme yap
      foreach ($table_fieldnames as $fieldname) {
      	$table_and_fieldname = $table . "." . $fieldname;
        $this->_select[$table_and_fieldname] = $table_and_fieldname;
      }

      // join işleminde tüm tabloları arka arkaya ekle
      $this->_table .= "," . $table;
    }

    // tablonun kendi select için eklemeler yap
    foreach (ApplicationSql::fieldnames(static::$name) as $fieldname) {
      $this->_select[static::$name . "." . $fieldname] = static::$name . "." . $fieldname;
    }

    return $this;
  }

  public function group($field) {
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

  // this function DRAFT
  public function delete_all() {
  //public static function delete_all($conditions) {
    //self::check_fieldnames(array_keys($conditions));

    //$sets = self::implode_key_and_value($conditions, "and");
    //ApplicationSql::delete(static::$name, $sets);
    $conditions = $this->where ? $this->where : "";
    ApplicationSql::delete(static::$name, $conditions);
  }

  //////////////////////////////////////////////////
  // Public Static Functions
  //////////////////////////////////////////////////

  // echo User::primary_keyname();

  // ok
  public static function primary_keyname() {
    return ApplicationSql::primary_keyname(static::$name);
  }

  // TODO
  public static function create($conditions) {

  }

  // query primary_key
  //
  // $user = User::find(1); // return User objects
  // $user->first_name = 'foo';
  // $user->save();

  // ok
  public static function find($primary_key) {

    if ($record = ApplicationSql::read(static::$name, "id = " . $primary_key)->fetch(PDO::FETCH_ASSOC)) {
      $object = static::$name::load();
      foreach ($record as $fieldname => $value) {
        $object->$fieldname = $value;
      }
      $object->_new_record_state = false;
      return $object;
    }
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

  // ok
  public static function delete($primary_key) {
    ApplicationSql::delete(static::$name, "id = " . $primary_key);
  }

  //////////////////////////////////////////////////
  // Private Functions
  //////////////////////////////////////////////////
  // name check_join_table_and_field
  private function check_table_and_field($conditions) {
    foreach ($conditions as $field => $value) {
      if (strpos($field, '.') !== false) {
        list($request_table, $request_field) = array_map('trim', explode('.', $field));

        self::check_tablename($request_table);
        self::check_fieldname($request_field, $request_table);
      } else {
        $conditions[static::$name . '.' .  $field] = trim($value);
        unset($conditions[$field]);
      }
    }
    return $conditions;
  }

  private function check_id(){
    if (!in_array("id", array_keys($this->_fields)))
      throw new FieldNotFoundException("İşlem sırasında birincil anahtar mevcut değil", "id");
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

  private function field_exists($field) {
    return in_array($field, ApplicationSql::fieldnames(static::$name)) ? true : false;
  }

  // ok
  private function implode_key_and_value($conditions, $delimiter = ",") {
    return implode(" $delimiter ", array_map(function ($key, $value) { return $key . "=" . $value; }, array_keys($conditions), $conditions));
  }
}
?>

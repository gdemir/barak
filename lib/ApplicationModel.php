s<?php
//
class ApplicationModel {

  private $_select = []; // list
  private $_table  = ""; // string
  private $_where  = []; // hash
  private $_join  =  []; // hash
  private $_group  = []; // list
  private $_limit;
  private $_order  = []; // list

  private $_fields;
  private $_new_record_state;

  //////s////////////////////////////////////////////
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

  public function __construct($fields = null) {
    $this->_new_record_state = true;

    foreach (ApplicationSql::fieldnames(static::$name) as $fieldname) {
      if ($fields) {

          // simple load for create
        $this->_fields[$fieldname] = in_array($fieldname, array_keys($fields)) ? $fields[$fieldname] : "";

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

    $records = ApplicationSql::query($this->_select, $this->_table, $this->_join, $this->_where, $this->_order, $this->_group, $this->_limit);

    if ($records) {
      foreach ($records as $record) {
        $object = $this->_table::find((int)$record["id"]);
        $object->_fields = $record;
        $objects[] = $object;
      }
      return isset($objects) ? $objects : null;
    }
    return null;
  }


  public function __get($field) {
    if (isset($this->_fields[$field])) {
      return $this->_fields[$field];
    } else if (in_array(ucfirst($field), ApplicationSql::tablenames())) {

      $belong_table = ucfirst($field);
      $owner_key = strtolower($this->_table) . "_id";

      if (!in_array($owner_key, self::table_belongs($belong_table)))
        throw new BelongNotFoundException("Tabloya ait olan böyle bir tablo yok", $field);

      $this->_where = [$owner_key => $this->_fields["id"]];
      $this->_table = $belong_table;

      $records = ApplicationSql::query($this->_select, $this->_table, $this->_join, $this->_where, $this->_order, $this->_group, $this->_limit);

      if ($records) {

        foreach ($records as $record) {
          $object = $this->_table::find((int)$record["id"]);
          $objects[] = $object;
        }

        return isset($objects) ? $objects : null;
      }

      return null;

    } else {
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $field);
    }
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

      ApplicationSql::update(static::$name, $this->_fields, ["id" => $this->_fields["id"]]);

    } else {

      // kayıt tamamlandıktan sonra en son id döndür
      $primary_key = ApplicationSql::create(static::$name, $this->_fields);

      // artık yeni kayıt değil
      $this->_new_record_state = false;

      // id'si olan kaydın alan değerlerini yeni kayıtta güncelle, (ör.: id otomatik olarak alması için)
      $this->_fields = static::$name::find($primary_key)->_fields;
    }
  }

  // $users = User::load()->select("first_name, last_name")->get();
  // ["User.firs_name, User.last_name"]

  // ok
  public function select($fields) {

    $fields = self::check_fields_of_table_list(array_map('trim', explode(',', $fields)));

    // varsayılan olarak ekle, objeler yüklenirken her zaman id olmalıdır.
    $table_and_primary_key = static::$name . ".id";
    if (!in_array($table_and_primary_key, $fields))
      array_push($fields, $table_and_primary_key);

    $this->_select = $fields; // ["User.first_name", "User.last_name", "Comment.name"]

    return $this;
  }

  // ok
  public function where($fields = null) {
    $fields = self::check_fields_of_table_hash($fields);

    $this->_where = ($this->_where) ? array_merge($this->_where, $fields) : $fields;
    return $this;
  }

  private function belongs_of_fieldnames($table_fieldnames) {
    return preg_grep("/(.*)_id/", $table_fieldnames);
  }


  private function table_belongs($table) {
    return self::belongs_of_fieldnames(ApplicationSql::fieldnames($table));
  }

  // ok
  public function joins($tables) {

    $table_belong_array = [];
    foreach ($tables as $table) {
      $table_fieldnames = ApplicationSql::fieldnames($table);
      $table_belongs = self::belongs_of_fieldnames($table_fieldnames);

      // join işlemi için User.id = Comment.user_id gibi where'ye eklemeler yap
      foreach ($table_belongs as $table_belong) {
        $this->_join[$table] = $table . "." . $table_belong . "=" . ucfirst(str_replace("_", ".", $table_belong));
        //$this->_where[$table . "." . $table_belong] = ucfirst(str_replace("_", ".", $table_belong));
      }

      // join işleminde select çakışması önlenmesi için User.first_name, User.last_name gibi ekleme yap
      foreach ($table_fieldnames as $fieldname)
        $this->_select[] = $table . "." . $fieldname;

      // join işleminde tüm tabloları listeye ekle
      //array_push($this->_table, $table);
    }

    // tablonun kendi select için eklemeler yap
    foreach (ApplicationSql::fieldnames(static::$name) as $fieldname) {
      $this->_select[] = static::$name . "." . $fieldname;
    }

    return $this;
  }

  public function group($fields) {
    $fields = self::check_fields_of_table_list(array_map('trim', explode(',', $fields)));

    $this->_group = $fields;
    return $this;
  }

  // ok
  public function limit($count = null) {
    $this->_limit = [$count];
    return $this;
  }

  // ok
  public function order($field, $sort_type = "asc") {
    // TODO $sort_type check asc, desc ??
    self::check_fieldname($field);

    $this->_order[] = "$field $sort_type";
    return $this;
  }

  // ok
  public static function first($limit = 1) {
    $this->_order[] = "id asc";
    $this->_limit = [$limit];
    return $this;
  }

  // ok
  public function last($limit = 1) {
    $this->_order[] = "id desc limit " . $limit;
    return $this;
  }

  // this function DRAFT
  public function delete_all() {
  //public static function delete_all($fields) {
    //self::check_fieldnames(array_keys($fields));

    //$sets = self::implode_key_and_value($fields, "and");
    //ApplicationSql::delete(static::$name, $sets);
    $fields = $this->where ? $this->where : "";
    ApplicationSql::delete(static::$name, $fields);
  }

  //////////////////////////////////////////////////
  // Public Static Functions
  //////////////////////////////////////////////////

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

  // ok
  public static function find(int $primary_key) {

    if ($record = ApplicationSql::read(static::$name, ["id" => $primary_key])) {
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
    return static::$name::load()->get();
  }

  public static function exists($primary_key) {
    return static::$name::find($primary_key) ? true : false;
  }

  // ok
  public static function update($primary_key, $fields) {
    self::check_fieldnames(array_keys($fields));

    ApplicationSql::update(static::$name, $fields, ["id" => $primary_key]);
  }

  // ok
  public static function delete($primary_key = null) {
    ApplicationSql::delete(static::$name, ["id" => $primary_key]);
  }

  //////////////////////////////////////////////////
  // Private Functions
  //////////////////////////////////////////////////
  // name check_join_table_and_field

  // select, where, group, order by


  private function check_fields_of_table_list($fields) {
    foreach ($fields as $index => $field) {
      if (strpos($field, '.') !== false) { // found TABLE
        list($request_table, $request_field) = array_map('trim', explode('.', $field));

        if ($request_table != $this->_table and ($this->_join and !array_key_exists($request_table, $this->_join)))
          throw new TableNotFoundException("Sorgulama işleminde böyle bir tablo mevcut değil", $request_table);

        self::check_fieldname($request_field, $request_table);

      } else {
        $fields[$index] = static::$name . '.' .  $field;
      }
    }
    return $fields;
  }

  private function check_fields_of_table_hash($fields) {
    foreach ($fields as $field => $value) {
      if (strpos($field, '.') !== false) { // found TABLE
        list($request_table, $request_field) = array_map('trim', explode('.', $field));

        if ($request_table != $this->_table and ($this->_join and !array_key_exists($request_table, $this->_join)))
          throw new TableNotFoundException("WHERE işleminde böyle bir tablo mevcut değil", $request_table);

        self::check_fieldname($request_field, $request_table);

      } else {
        $fields[static::$name . '.' .  $field] = $value;
        unset($fields[$field]);
      }
    }
    return $fields;
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
  private function implode_key_and_value($fields, $delimiter = ",") {
    return implode(" $delimiter ", array_map(function ($key, $value) { return $key . "=" . $value; }, array_keys($fields), $fields));
  }
}
?>
<?php
//
class ApplicationModel {

  private $_select = [];  // list
  private $_table  = "";  // string
  private $_where  = [];  // hash
  private $_join   = [];  // hash
  private $_group  = [];  // list
  private $_limit;
//  private $_offset;
  private $_order  = [];  // list

  private $_fields;
  private $_new_record_state;

  //////////////////////////////////////////////////
  // System Main Functions
  //////////////////////////////////////////////////

  // Ekleme ör.: 1

  // $user = User::new();
  // $user->first_name ="Gökhan";
  // $user->last_name ="Demir";
  // $user->save(); // yeni kayıt eklendi

  // Ekleme ör.: 2

  // $user = User::new(["last_name" => "Demir"]);
  // $user->first_name ="Gökhan";
  // $user->save(); // yeni kayıt eklendi

  // Ekleme ör.: 3

  // $user = User::new(["last_name" => "Demir"])->save();

  // Günceleme ör.: 1

  // $this->users = User::all(); // tüm users içinde id olduğu için kayıtları güncelleme özelliği oluyor.
  // foreach ($this->users as $user) {
  //   $user->first_name = "Gökhan";
  //   $user->save();
  // }

  private function __construct($fields = null) {
    $this->_new_record_state = true;

    foreach (ApplicationSql::fieldnames(get_called_class()) as $fieldname) {
      if ($fields) {

        // simple load for create
        $this->_fields[$fieldname] = in_array($fieldname, array_keys($fields)) ? $fields[$fieldname] : null;

      } else {

        // create draft fieldnames
        $this->_fields[$fieldname] = null;

      }
    }
  }

  // Alma 1:

  // $user = User::find(1);
  // echo $user->_first_name;

  // Alma 2:

  // $comment = Comment::find(1); // ["id", "name", "content", "user_id"]
  // echo $comment->user->first_name;

  public function __get($field) {
    if (array_key_exists($field, $this->_fields)) {
      return $this->_fields[$field];
    } else if (in_array(ucfirst($field), ApplicationSql::tablenames())) { // Büyükten -> Küçüğe

      $belong_table = ucfirst($field); // User
      $foreign_key = strtolower($field) . "_id"; //user_id

      if (!in_array($foreign_key, ApplicationSql::fieldnames($this->_table)))
        throw new BelongNotFoundException("Tabloya ait olan böyle foreign key yok", $foreign_key);

      return $belong_table::find($this->_fields[$foreign_key]);

    } else {
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $field);
    }
  }

  public function __set($field, $value) {
    if (array_key_exists($field, $this->_fields))
      $this->_fields[$field] = $value;
    else
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $field);
  }

  //////////////////////////////////////////////////
  // Public Functions
  //////////////////////////////////////////////////

  // FETCH Functions Start

  // ok
  public function take() {

    $records = self::query();

    if ($records) {
      foreach ($records as $record) {
        $object = $this->_table::find((int)$record["id"]);
        $object->_fields = $record;
        $objects[] = $object;
      }
      return $objects;
    }
    return null;
  }

  // ok
  public function pluck($fieldname) {

    self::check_fieldname($fieldname);

    $this->_select = [$fieldname];
    $records = self::query();

    if ($records) {
      foreach ($records as $record)
        $values[] = $record[$fieldname];

      return $values;
    }
    return null;
  }

  // ok
  public function count() {
    $this->_select = ["count(*)"];

    $record = ApplicationSql::read($this->_table, $this->_select, $this->_where);

    return $record["count(*)"] ?: null;
  }

  // FETCH Functions End

  // $user = new User();
  // $user->first_name ="Gökhan";
  // $user->save(); // kayıt ettikten sonra otomatik id değeri alır.

  // print_r($user);
  // [_fields:ApplicationModel:private] => Array ( [first_name] => Gökhan [last_name] => [username] => [password] => [content] => [id] => 368 )

  // ok
  public function save() {

    if (!$this->_new_record_state) {

      ApplicationSql::update($this->_table, $this->_fields, ["id" => $this->_fields["id"]]);

    } else {

      // kayıt tamamlandıktan sonra en son id döndür
      $primary_key = ApplicationSql::create($this->_table, $this->_fields);

      // artık yeni kayıt değil
      $this->_new_record_state = false;

      // id'si olan kaydın alan değerlerini yeni kayıtta güncelle, (ör.: id otomatik olarak alması için)
      $this->_fields = $this->_table::find($primary_key)->_fields;
    }
  }

  // ok
  public function destroy() {
    ApplicationSql::delete($this->_table, ["id" => $this->_fields["id"]], null);
  }

  // $users = User::load()->select("first_name, last_name")->get();
  // ["User.firs_name, User.last_name"]

  // ok
  public function select($fields) {

    $fields = self::check_fields_of_table_list(array_map('trim', explode(',', $fields)));

    // varsayılan olarak ekle, objeler yüklenirken her zaman id olmalıdır.
    $table_and_primary_key = $this->_table . ".id";
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

  // ok
  public function joins($belong_tables) {

    $table = $this->_table;
    foreach ($belong_tables as $index => $belong_table) {

      $belong_table_fieldnames = ApplicationSql::fieldnames($belong_table);
      $foreign_key = strtolower($table) . "_id";

      if (!in_array($foreign_key, $belong_table_fieldnames))
        throw new BelongNotFoundException("Sorgulama işleminde böyle bir tablo mevcut değil", $foreign_key);

      // join işlemi için User.id = Comment.user_id gibi where'ye eklemeler yap
      $this->_join[$belong_table] = $belong_table . "." . $foreign_key . "=" . ucfirst(str_replace("_", ".", $foreign_key));

      // // join işleminde select çakışması önlenmesi için User.first_name, User.last_name gibi ekleme yap
      foreach ($belong_table_fieldnames as $fieldname)
        $this->_select[] = $belong_table . "." . $fieldname;

      $table = $belong_table;
    }

    // tablonun kendi select için eklemeler yap
    foreach (ApplicationSql::fieldnames($this->_table) as $fieldname)
      $this->_select[] = $this->_table . "." . $fieldname;

    return $this;
  }

  public function group($fields) {
    $fields = self::check_fields_of_table_list(array_map('trim', explode(',', $fields)));

    $this->_group = $fields;
    return $this;
  }

  // ok
  public function limit($limit = null) {
    $this->_limit = $limit;
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
  public function delete_all() {
    ApplicationSql::delete($this->_table, $this->_where, null);
  }

  //////////////////////////////////////////////////
  // Public Static Functions
  //////////////////////////////////////////////////

  public static function unique($fields = null) {
    if ($record = ApplicationSql::read(get_called_class(), null, $fields)) {
      $object = get_called_class()::load();

      foreach ($record as $fieldname => $value)
        $object->$fieldname = $value;

      $object->_new_record_state = false;
      return $object;
    }
    return null;
  }

  // echo User::primary_keyname();

  // ok
  public static function primary_keyname() {
    return ApplicationSql::primary_keyname(get_called_class());
  }

  // Ör. 1:

  // $user = User::new();
  // $user->first_name = "Gökhan";
  // $user->save();
  // print_r($user); // otomatik id alır

  // Ör. 2:

  // $user = User::new(["first_name" => "Gökhan"])->save();

  public static function new($fields = null) {
    $classname = get_called_class();
    $object = new $classname($fields);
    $object->_table = get_called_class();
    return $object;
  }

  public static function load() {
    return self::new(null);
  }

  // User::create(["first_name" => "Gökhan"]);

  // ok
  public static function create($fields) {
  	$object = get_called_class()::new($fields);
  	$object->save();
    return $object;
  }

  // User::first();

  // ok
  public static function first($limit = 1) {
    $records = get_called_class()::load()->order("id asc")->limit($limit)->take();
    return ($limit == 1) ? $records[0] : $records;
  }

  // User::last();

  // ok
  public static function last($limit = 1) {
    $records = get_called_class()::load()->order("id desc")->limit($limit)->take();
    return ($limit == 1) ? $records[0] : $records;
  }

  // $user = User::find(1); // return User objects
  // $user->first_name = 'foo';
  // $user->save();

  // ok
  public static function find($primary_key) {
    return get_called_class()::unique(["id" => intval($primary_key)]);
  }

  // $users = User::find_all([1, 2, 3]); // return User objects array
  //
  // foreach ($users as $user) {
  //   $user->first_name = "Gökhan";
  //   $user->save();
  // }

  public static function find_all($primary_keys) {

    foreach ($primary_keys as $primary_key)
      $objects[] = get_called_class()::find($primary_key);

    return isset($objects) ? $objects : null;
  }

  // $users = User::all(); // return User objects array
  //
  // foreach ($users as $user) {
  //   $user->first_name = "Gökhan";
  //   $user->save();
  // }

  // ok
  public static function all() {
    return get_called_class()::load()->take();
  }

  // ok
  public static function exists($primary_key) {
    return get_called_class()::find($primary_key) ? true : false;
  }

  // ok
  public static function update(int $primary_key, $fields) {
    self::check_fieldnames(array_keys($fields));

    ApplicationSql::update(get_called_class(), $fields, ["id" => intval($primary_key)]);
  }

  // ok
  public static function delete(int $primary_key) {
    ApplicationSql::delete(get_called_class(), ["id" => intval($primary_key)], null);
  }

  //////////////////////////////////////////////////
  // Private Functions
  //////////////////////////////////////////////////

  private function query() {
    return ApplicationSql::query($this->_select, $this->_table, $this->_join, $this->_where, $this->_order, $this->_group, $this->_limit);
  }

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
        $fields[$index] = get_called_class() . '.' .  $field;
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
        $fields[get_called_class() . '.' .  $field] = $value;
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
    $table = $table ?? get_called_class();
    if (!in_array($field, ApplicationSql::fieldnames($table)))
      throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $table . "." . $field);
  }

  private function check_fieldnames($fields, $table = null) {
    $table = $table ?? get_called_class();
    $table_fields = ApplicationSql::fieldnames($table);
    foreach ($fields as $field) {
      if (!in_array($field, $table_fields))
        throw new FieldNotFoundException("Tabloda böyle bir anahtar mevcut değil", $table . "." . $field);
    }
  }

  private function field_exists($field) {
    return in_array($field, ApplicationSql::fieldnames(get_called_class())) ? true : false;
  }
}
?>
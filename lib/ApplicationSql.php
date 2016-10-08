<?php
// CRUD
// DRAFT #TODO or builder : https://github.com/ryangurn/PHP-MVC/blob/master/libraries/activerecord/lib/SQLBuilder.php

// #TODO http://php.net/manual/en/pdo.prepare.php for php real_escape_string()
/*
SQL injection protection http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
$sth = $dbh->prepare('SELECT name, colour, calories
    FROM fruit
    WHERE calories < ? AND colour = ?');
$sth->execute(array(150, 'red'));
$red = $sth->fetchAll();

*/

class ApplicationSql {

  private function hash_to_symbols($_hash) {
    $key_and_symbols = "";       // ["first_name" => ":first_name", "last_name" => ":last_name"]
    $symbol_and_values = [];     // [":first_name" => "Gökhan", ":last_name" => "Demir"]

    foreach ($_hash as $key => $value) {

      $key_symbol = ":$key";
      $key_and_symbols .= ($symbol_and_values ? "," : "") . "`$key`=:$key";
      $symbol_and_values[$key_symbol] = $value;

    }

    return array($key_and_symbols, $symbol_and_values);
  }

  public static function create($_table, $_fields) {

  // $GLOBALS["db"]->query(
  //   "insert into " . $table .
  //   " (" . $fields . ") " .
  //   "values(" . $values . ")"
  //   );

    if (array_key_exists("id", $_fields)) unset($_fields["id"]);

    // $fields = "";                   // "`first_name`,`last_name`"
    // $field_symbols = "";            // ":first_name,:last_name"
    // $field_symbol_and_values = [];  // [":first_name" => "Gökhan", ":last_name" => "Demir"]

    // foreach ($_fields as $field => $value) {
    //   $fields .= ($fields ? "," : "") . "`" . $field . "`";

    //   $field_symbol = ":$field";
    //   $field_symbols .= ($field_symbols ? "," : "") . $field_symbol;
    //   $field_symbol_and_values[$field_symbol] = $value;
    // }
    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("INSERT INTO `$_table` SET $field_key_and_symbols");


    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);

    return $GLOBALS["db"]->lastInsertId();
  }

  public static function read($_table, $_fields) {

    // return $GLOBALS["db"]->query(
    //   "select * from " . $table .
    //   " where " . $conditions
    //   );

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("SELECT * FROM `$_table` WHERE $field_key_and_symbols");

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);

    return $query;
  }

  public static function update($_table, $_sets, $_fields) {

    // $GLOBALS['db']->query(
    //   "update " . $table .
    //   " set " . $sets .
    //   " where " . $fields
    //   );

    list($set_key_and_symbols, $set_symbol_and_values) = self::hash_to_symbols($_sets);

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("UPDATE `$_table` SET $set_key_and_symbols WHERE $field_key_and_symbols");

    if (!$query->execute(array_merge($field_symbol_and_values, $set_symbol_and_values)))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);
  }

  public static function delete($_table, $_fields) {
    // $GLOBALS['db']->query(
    //   "delete from " . $table .
    //   " where " . $fields
    //   );

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("DELETE FROM `$_table` WHERE $field_key_and_symbols");

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);

  }

  public static function query($query) {
    return $GLOBALS['db']->query($query);
  }

  public static function describe($table) {
    return $GLOBALS['db']->query("describe " . $table);
  }

  public static function tablenames() {
    return $GLOBALS['db']->tablenames();
  }

  public static function fieldnames($table) {
    return self::describe($table)->fetchAll(PDO::FETCH_COLUMN);
  }

  public static function primary_keyname($table) {
    return $GLOBALS['db']->query("show index from " . $table . " where Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
  }

}
?>
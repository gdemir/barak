<?php
// CRUD
// DRAFT #TODO or builder : https://github.com/ryangurn/PHP-MVC/blob/master/libraries/activerecord/lib/SQLBuilder.php

// #TODO http://php.net/manual/en/pdo.prepare.php for php real_escape_string()
/*

$sth = $dbh->prepare('SELECT name, colour, calories
    FROM fruit
    WHERE calories < ? AND colour = ?');
$sth->execute(array(150, 'red'));
$red = $sth->fetchAll();

*/

class ApplicationSql {

  public static function create($table, $fields, $values) {
    $GLOBALS["db"]->query(
      "insert into " . $table .
      " (" . $fields . ") " .
      "values(" . $values . ")"
      );
    return $GLOBALS["db"]->lastInsertId();
  }

  public static function read($table, $conditions) {
    return $GLOBALS["db"]->query(
      "select * from " . $table .
      " where " . $conditions
      );
  }

  public static function update($table, $sets, $conditions) {
    $GLOBALS['db']->query(
      "update " . $table .
      " set " . $sets .
      " where " . $conditions
      );
  }

  public static function delete($table, $conditions) {
    $GLOBALS['db']->query(
      "delete from " . $table .
      " where " . $conditions
      );
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

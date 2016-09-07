<?php
// CRUD
// DRAFT #TODO or builder : https://github.com/ryangurn/PHP-MVC/blob/master/libraries/activerecord/lib/SQLBuilder.php
class ApplicationSql {

  public static create($fields, $values) {
    $GLOBALS["db"]->query(
        "insert into " . static::$name .
        " (" . $fields . ") " .
        "values(" . $values . ")"
        );
  }

  public static read($conditions) {
    $GLOBALS["db"]->query(
        "select * from " . static::$name .
        " where " . $conditions
        );
  }

  public static update($sets, $conditions) {
    $GLOBALS['db']->query(
        "update " . static::$name .
        " set " . $sets .
        " where " . $conditions
        );
  }
  
  public static delete($conditions) {
    $GLOBALS['db']->query(
        "delete from " . static::$name .
        " where " . $conditions
        );
  }

}

?>

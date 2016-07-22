<?php
// DRAFT #TODO
class ApplicationSql {

  public static insert($fields, $values) {
    $GLOBALS["db"]->query(
        "insert into " . static::$name .
        " (" . $fields . ") " .
        "values(" . $values . ")"
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

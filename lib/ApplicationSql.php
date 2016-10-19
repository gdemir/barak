<?php
// CRUD
// DRAFT #TODO or builder : https://github.com/ryangurn/PHP-MVC/blob/master/libraries/activerecord/lib/SQLBuilder.php

// SQL injection protection http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php

class ApplicationSql {

  private function hash_to_symbols($_hash, $delimiter = ",", $command = "") { // ["first_name" => "Gökhan", "last_name" => "Demir"]
    $key_and_symbols = "";       // ["first_name" => ":first_name", "last_name" => ":last_name"]
    $symbol_and_values = [];     // [":first_name" => "Gökhan", ":last_name" => "Demir"]

    foreach ($_hash as $key => $value) {

      $key_symbol = ":$command" . "_" . str_replace(".", "_", $key);
      $key_and_symbols .= ($symbol_and_values ? " $delimiter " : "") . "$key=$key_symbol";
      $symbol_and_values[$key_symbol] =  $value;

    }

    return array($key_and_symbols ? "$command $key_and_symbols" : "", $symbol_and_values);
  }

  private function list_to_symbols($_list, $command = "") { // ["first_name", "last_name"]
    $symbols = "";                           // [":first_name", ":last_name"]
    $symbol_and_values = [];                 // [":first_name" => "first_name", ":last_name" => "last_name"]

    foreach ($_list as $field) {

      $key_symbol = ":" . str_replace(" ", "", $command) . "_" . str_replace(".", "_", str_replace(" ", "_", $field));
      $symbols .= ($symbols ? "," : "") . $key_symbol;
      $symbol_and_values[$key_symbol] = $field;

    }

    return array($symbols ? "$command $symbols" : "", $symbol_and_values);
  }

  private function var_to_symbol($_var, $command = "") {

    if ($_var) {
      $symbol = ":" . $command . "_" . $_var;
      return array($symbol, "$command $symbol", [$symbol => $_var]);
    } else {
      return array("", "", []);
    }
  }

  public static function create($_table, $_fields) {

    if (array_key_exists("id", $_fields)) unset($_fields["id"]);

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("INSERT INTO $_table SET $field_key_and_symbols");

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);

    return intval($GLOBALS["db"]->lastInsertId());
  }

  public static function read($_table, $_fields) {

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields, "and");

    $query = $GLOBALS['db']->prepare("SELECT * FROM $_table WHERE $field_key_and_symbols");

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);

    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public static function update($_table, $_sets, $_fields) {

    list($set_key_and_symbols, $set_symbol_and_values) = self::hash_to_symbols($_sets);

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields, ",");

    $query = $GLOBALS['db']->prepare("UPDATE `$_table` SET $set_key_and_symbols WHERE $field_key_and_symbols");

    if (!$query->execute(array_merge($field_symbol_and_values, $set_symbol_and_values)))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);
  }

  public static function delete($_table, $_fields) {

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("DELETE FROM `$_table` WHERE $field_key_and_symbols");

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);
  }

  public static function query($_select, $_table, $_join, $_where, $_order, $_group, $_limit) {

    $_select = $_select ?: ["*"];
//    $_limit = $_limit ?: [];

    $_select = implode(",", $_select);

    if ($_join) {
      $_join_commands = "";
      foreach ($_join as $table => $condition) {
        $_join_commands .= ($_join_commands ? " " : "") . "INNER JOIN $table ON $condition";;
      }
    } else {
      $_join_commands = "";
    }

    list($where_command_key_and_symbols, $where_symbol_and_values) = self::hash_to_symbols($_where, "and", "WHERE");

    list($order_command_symbols, $order_symbol_and_values) = self::list_to_symbols($_order, "ORDER BY");

    list($group_command_symbols, $group_symbol_and_values) = self::list_to_symbols($_group, "GROUP BY");

    list($limit_symbol, $limit_command_symbol, $limit_symbol_and_value) = self::var_to_symbol($_limit, "LIMIT");

    $sql = "
    SELECT $_select
    FROM $_table
    $_join_commands
    $where_command_key_and_symbols
    $order_command_symbols
    $group_command_symbols
    $limit_command_symbol";

    echo "çalış-><br/><br/>";
    echo $sql;
    echo "<br/><br/>çalıştı<br/><br/>";

    // $sql = "SELECT User.first_name, User.id FROM  User INNER JOIN Comment ON Comment.user_id=User.id";
    $query = $GLOBALS['db']->prepare($sql);
    // print_r($query);

    // print_r($query->queryString);
    // echo "<br/>";echo "<br/>";echo "<br/>";
    // print_r($sql);
    // echo "<br/>";echo "<br/>";echo "<br/>";

    // $b = $GLOBALS['db']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    // print_r($b);
    // print_r($where_symbol_and_values);

    if ($_limit) {
      $query->bindParam($limit_symbol, $limit_symbol_and_value[$limit_symbol], PDO::PARAM_INT);
    }

    $symbol_and_values = array_merge(
      $where_symbol_and_values,
      $order_symbol_and_values,
      $group_symbol_and_values
      );

    foreach ($symbol_and_values as $symbol => $value) {
      $query->bindParam($symbol, $value);
    }
    print_r($symbol_and_values);

    if (!$query->execute())
      throw new SQLException("Tabloya veri getirmede sorun oluştu", $_table);

    // // echo "<br/>";echo "<br/>";echo "<br/>";
    // // print_r($query);
    // // echo "<br/>";echo "<br/>";echo "<br/>";

    return $query->fetchAll(PDO::FETCH_ASSOC);
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

<?php
// CRUD
// DRAFT #TODO or builder : https://github.com/ryangurn/PHP-MVC/blob/master/libraries/activerecord/lib/SQLBuilder.php

// SQL injection protection http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php

class ApplicationSql {

  // ["first_name" => "Gökhan", "last_name" => "Demir"]
  private function hash_to_key_symbol_symbolvalue($_hash, $delimiter = ",", $command = "") {
    $symbols = "";                             // ["first_name" => ":first_name", "last_name" => ":last_name"]
    $symbol_and_values = [];                   // [":first_name" => "Gökhan", ":last_name" => "Demir"]
    $keys = "";
    foreach ($_hash as $key => $value) {
      $keys .= ($keys ? " $delimiter " : "") . $key;
      $key_symbol = ":$command" . "_" . str_replace(".", "_", $key);
      $symbols .= ($symbols ? " $delimiter " : "") . $key_symbol;
      $symbol_and_values[$key_symbol] = $value;

    }

    return array($keys, $symbols, $symbol_and_values);
  }

  // ["first_name" => "Gökhan", "last_name" => "Demir"]
  private function hash_to_keysymbol_symbolvalue($_hash, $delimiter = ",", $command = "") {
    $key_and_symbols = "";                     // ["first_name" => ":first_name", "last_name" => ":last_name"]
    $symbol_and_values = [];                   // [":first_name" => "Gökhan", ":last_name" => "Demir"]

    foreach ($_hash as $key => $value) {

      $key_symbol = ":$command" . "_" . str_replace(".", "_", $key);
      $key_and_symbols .= ($symbol_and_values ? " $delimiter " : "") . "$key=$key_symbol";
      $symbol_and_values[$key_symbol] =  $value;

    }

    return array($key_and_symbols ? "$command $key_and_symbols" : "", $symbol_and_values);
  }

  // ["first_name", "last_name"]
  private function list_to_symbol_symbolvalue($_list, $command = "") {
    $symbols = "";                             // ":first_name , :last_name"
    $symbol_and_values = [];                   // [":first_name" => "first_name", ":last_name" => "last_name"]
    $command = str_replace(" ", "", $command); // ORDER BY => ORDERBY, GROUP BY => GROUPBY

    foreach ($_list as $field) {

      $key_symbol = ":$command" . "_" . str_replace(".", "_", str_replace(" ", "_", $field));
      $symbols .= ($symbols ? "," : "") . $key_symbol;
      $symbol_and_values[$key_symbol] = $field;

    }

    return array($symbols ? "$command $symbols" : "", $symbol_and_values);
  }

  private function var_to_symbol($_var, $command = "") {

    if ($_var) {
      $symbol = ":$command" . "_" . $_var;
      return array($symbol, "$command $symbol", [$symbol => $_var]);
    } else {
      return array("", "", []);
    }
  }

  public static function create($_table, $_fields) {

    if (array_key_exists("id", $_fields)) unset($_fields["id"]);

    foreach ($_fields as $field => $value) if ($value == null) unset($_fields[$field]);

    list($field_keys, $field_symbols, $field_symbol_and_values) = (new ApplicationSql)->hash_to_key_symbol_symbolvalue($_fields);

    $query = $GLOBALS['db']->prepare("INSERT INTO $_table ( $field_keys ) VALUES ( $field_symbols )");

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya kayıt yazmada sorun oluştu", $_table);

    return intval($GLOBALS["db"]->lastInsertId());
  }

  public static function read($_table, $_select, $_where) {

    if (empty($_select)) $_select = ["*"];
    $_select = implode(",", $_select);

    list($where_key_and_symbols, $where_symbol_and_values) = (new ApplicationSql)->hash_to_keysymbol_symbolvalue($_where, "and", "WHERE");

    $query = $GLOBALS['db']->prepare("SELECT $_select FROM $_table $where_key_and_symbols");

    if (!$query->execute($where_symbol_and_values))
      throw new SQLException("Tablodan veri okumasında sorun oluştu", $_table);

    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public static function update($_table, $_sets, $_where) {

    list($set_key_and_symbols, $set_symbol_and_values) = (new ApplicationSql)->hash_to_keysymbol_symbolvalue($_sets);

    list($where_key_and_symbols, $where_symbol_and_values) = (new ApplicationSql)->hash_to_keysymbol_symbolvalue($_where, ",");

    $query = $GLOBALS['db']->prepare("UPDATE `$_table` SET $set_key_and_symbols WHERE $where_key_and_symbols");

    if (!$query->execute(array_merge($where_symbol_and_values, $set_symbol_and_values)))
      throw new SQLException("Tabloda kayıt güncellemesinde sorun oluştu", $_table);
  }

  public static function delete($_table, $_fields, $_limit) {

    list($where_key_and_symbols, $where_symbol_and_values) = (new ApplicationSql)->hash_to_keysymbol_symbolvalue($_fields, "and", "WHERE");
    list($limit_symbol, $limit_command_symbol, $limit_symbol_and_value) = (new ApplicationSql)->var_to_symbol($_limit, "LIMIT");

    $query = $GLOBALS['db']->prepare("DELETE FROM `$_table` $where_key_and_symbols $limit_command_symbol");

    if ($_limit)
      $query->bindParam($limit_symbol, $limit_symbol_and_value[$limit_symbol], PDO::PARAM_INT);

    if (!$query->execute($where_symbol_and_values))
      throw new SQLException("Tablodan veri silmesinde sorun oluştu", $_table);
  }

  public static function query($_select, $_table, $_join, $_where, $_order, $_group, $_limit) {

    if (empty($_select)) $_select = ["*"];
    $_select = implode(",", $_select);

    if ($_join) {
      $_join_commands = "";
      foreach ($_join as $table => $condition) {
        $_join_commands .= ($_join_commands ? " " : "") . "INNER JOIN $table ON $condition";;
      }
    } else {
      $_join_commands = "";
    }

    list($where_command_key_and_symbols, $where_symbol_and_values) = (new ApplicationSql)->hash_to_keysymbol_symbolvalue($_where, "and", "WHERE");
    list($order_command_symbols, $order_symbol_and_values) = (new ApplicationSql)->list_to_symbol_symbolvalue($_order, "ORDER BY");
    list($group_command_symbols, $group_symbol_and_values) = (new ApplicationSql)->list_to_symbol_symbolvalue($_group, "GROUP BY");
    list($limit_symbol, $limit_command_symbol, $limit_symbol_and_value) = (new ApplicationSql)->var_to_symbol($_limit, "LIMIT");

    $sql = "
    SELECT $_select
    FROM $_table
    $_join_commands
    $where_command_key_and_symbols
    $order_command_symbols
    $group_command_symbols
    $limit_command_symbol";


    $query = $GLOBALS['db']->prepare($sql);

    if ($_limit)
      $query->bindParam($limit_symbol, $limit_symbol_and_value[$limit_symbol], PDO::PARAM_INT);

    $symbol_and_values = array_merge(
      $where_symbol_and_values,
      $order_symbol_and_values,
      $group_symbol_and_values
      );

    foreach ($symbol_and_values as $symbol => $value) {
      $query->bindParam($symbol, $value);
    }

    if (!$query->execute())
      throw new SQLException("Tablodan kayıtların okunmasında sorun oluştu", $_table);

    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function tablenames() {
    $name = $GLOBALS['db']->query("select database()")->fetchColumn();
    $result = $GLOBALS['db']->query("show tables");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) $tablenames[] = $row["Tables_in_" . $name];
    return $tablenames;
  }

  public static function fieldnames($table) {
    return $GLOBALS['db']->query("DESCRIBE $table")->fetchAll(PDO::FETCH_COLUMN);
  }

  public static function primary_keyname($table) {
    return $GLOBALS['db']->query("SHOW INDEX FROM $table WHERE Key_name = 'PRIMARY'")->fetch(PDO::FETCH_ASSOC)["Column_name"];
  }
}
?>
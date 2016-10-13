<?php
// CRUD
// DRAFT #TODO or builder : https://github.com/ryangurn/PHP-MVC/blob/master/libraries/activerecord/lib/SQLBuilder.php

// #TODO http://php.net/manual/en/pdo.prepare.php for php real_escape_string()

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

  public static function create($_table, $_fields) {

    if (array_key_exists("id", $_fields)) unset($_fields["id"]);

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields);

    $query = $GLOBALS['db']->prepare("INSERT INTO $_table SET $field_key_and_symbols");

    echo "##############<br/>";echo "##############<br/>";
    echo "INSERT INTO $_table SET $field_key_and_symbols";
    echo "##############<br/>";echo "##############<br/>";

    if (!$query->execute($field_symbol_and_values))
      throw new SQLException("Tabloya veri kaydında sorun oluştu", $_table);

    return intval($GLOBALS["db"]->lastInsertId());
  }

  public static function read($_table, $_fields) {

    list($field_key_and_symbols, $field_symbol_and_values) = self::hash_to_symbols($_fields, "and");

    $query = $GLOBALS['db']->prepare("SELECT * FROM $_table WHERE $field_key_and_symbols");

    echo "##############<br/>";echo "##############<br/>";
    echo "SELECT * FROM $_table WHERE $field_key_and_symbols";
    echo "##############<br/>";echo "##############<br/>";


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

  public static function query($_select, $_table, $_where, $_order, $_group, $_limit) {
    //$execute = ["SELECT" => $_select]
    $_select = $_select ?: ["id"];
    $_limit = $_limit ?: [];

    $_select = implode(",", $_select);
    $_table = implode(",", $_table);

    echo "<br/>";
    echo "<br/>";
    echo "select: "; print_r($_select);  echo "<br/>";
    echo "table:  "; print_r($_table);   echo "<br/>";
    echo "fields: "; print_r($_where);   echo "<br/>";
    echo "order: ";  print_r($_order);   echo "<br/>";
    echo "group: ";  print_r($_group);   echo "<br/>";
    echo "limit: ";  print_r($_limit);   echo "<br/>";
    echo "#####################################<br/>";

    list($where_key_and_symbols, $where_symbol_and_values) = self::hash_to_symbols($_where, "and", "WHERE");

    list($order_symbols, $order_symbol_and_values) = self::list_to_symbols($_order, "ORDER BY");
    echo $order_symbols; echo"<br/>"; print_r($order_symbol_and_values); echo " order<br/>";

    list($group_symbols, $group_symbol_and_values) = self::list_to_symbols($_group, "GROUP BY");
    echo $group_symbols; echo"<br/>"; print_r($group_symbol_and_values); echo " group<br/>";

    list($limit_symbols, $limit_symbol_and_values) = self::list_to_symbols($_limit, "LIMIT");
    echo $limit_symbols; echo"<br/>"; print_r($limit_symbol_and_values); echo " limit<br/>";

    //$sql = "SELECT $_select FROM $_table $where_key_and_symbols $order_symbols $group_symbols $limit_symbols";
    $query = $GLOBALS['db']->prepare("SELECT $_select FROM $_table $where_key_and_symbols $order_symbols $group_symbols $limit_symbols");

    echo "<br/> queryyy>>>>>>>>>>> <br/>";

    print_r($query);
    print_r($where_symbol_and_values);


    $a = array_merge(
      $where_symbol_and_values,
      $order_symbol_and_values,
      $group_symbol_and_values,
      $limit_symbol_and_values
      );

    if (!$query->execute($a))
      throw new SQLException("Tabloya veri getirmede sorun oluştu", $_table);

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
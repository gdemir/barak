<?php
class BARAK extends PDO {
  public function __construct($user, $pass, $name) {
    parent::__construct($user, $pass, $name);

    // DB configuration
    parent::query('set names "utf8"');
    parent::query('set character set "utf8"'); // dil secenekleri
    parent::query('set collation_connection = "utf8_general_ci"');
    parent::query('set collation-server = "utf8_general_ci"');
  }
  public function tablenames() {
    $name = parent::query("select database()")->fetchColumn();
    $result = parent::query("show tables");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) $tablenames[] = $row["Tables_in_" . $name];
    return $tablenames;
  }
}

// Global functions

function include_dynamical_segment($rule) {
  return strpos($rule, ":") ? true : false;
}

function resource($table) {
  return [
    new ApplicationRoute("get",  "$table/index", false),           // all record
    new ApplicationRoute("get",  "$table/new", false),             // new record form
    new ApplicationRoute("post", "$table/create", false),          // new record create
    new ApplicationRoute("get",  "$table/show/:id", false, true),  // display record
    new ApplicationRoute("get",  "$table/edit/:id", false, true),  // edit record
    new ApplicationRoute("post", "$table/update", false, false),   // update record
    new ApplicationRoute("post", "$table/destroy", false, false)   // destroy record
    ];
}

function post($rule, $target = false) {
  return new ApplicationRoute("post", $rule, $target, include_dynamical_segment($rule));
}

function get($rule, $target = false) {
  return new ApplicationRoute("get", $rule, $target, include_dynamical_segment($rule));
}
?>

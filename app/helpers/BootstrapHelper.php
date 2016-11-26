<?php

class BootstrapHelper {

  public static function breadcrumb_button() {

    $path = array_slice(explode("/", $_SERVER["REQUEST_URI"]), 1);
    $last_index = 2;

    $_list = "";
    $_value = "";
    foreach ($path as $index => $value) {
      $_value = $_value . "/" . $value;
      $_list .= (($index == $last_index) ? "<li active>$value</li>" : "<li><a href='$_value' class='btn btn-default btn-sm'>$value</a></li>");
    }
    return "<ol class='breadcrumb text-right'>" . $_list . "</ol>";
  }

  public static function page_title_and_breadcrumb() {

    $path = trim($_SERVER["REQUEST_URI"], "/");
    $path = explode("/", $path);

    $count_path = count($path);

    $title_index = ($count_path > 3) ? 2 : $count_path - 1;

    $_breadcrumb_list = "";
    $_link = "";
    $_value = "";
    $_values = "";
    foreach ($path as $index => $value) {
      $_link = $_link . "/" . $value;
      $_value = ($_value == "") ? $value : ($_value . "." . $value);
      $_values[] = $_value;

      if ($title_index == $index) {
        $_breadcrumb_list .= "<li active>" . t($_value . ".index") . "</li>";
        break;
      } else {
        $_breadcrumb_list .= "<li><a href='$_link'>" . t($_value . ".index") . "</a></li>";
      }
    }

    return "<h4 class='page-title'>" . t($_values[$title_index]. ".index") . "</h4><ol class='breadcrumb text-right'>" . $_breadcrumb_list . "</ol>";
  }

  public static function notice_show() {
    $messages = "";
    if (isset($_SESSION["danger"]))
      $messages .= "<div class='alert alert-danger' role='alert'>" . $_SESSION["danger"] . "</div>";
    if (isset($_SESSION["warning"]))
      $messages .= "<div class='alert alert-warning' role='alert'>" . $_SESSION["warning"] . "</div>";
    if (isset($_SESSION["success"]))
      $messages .= "<div class='alert alert-success' role='alert'>" . $_SESSION["success"] . "</div>";
    if (isset($_SESSION["info"]))
      $messages .= "<div class='alert alert-info' role='alert'>" . $_SESSION["info"] . "</div>";
    return $messages;
  }

  public static function notice_clear() {
    $keys = ["success", "warning", "danger", "info"];
    foreach ($keys as $key)
      unset($_SESSION[$key]);
  }

}
?>
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

    $path = array_slice(explode("/", $_SERVER["REQUEST_URI"]), 1); // home/index /home/categorypage/show/1

    $last_index = (count($path) > 2) ? 2 : 1;

    $_breadcrumb_list = "";
    $_value = "";
    foreach ($path as $index => $value) {
      $_value = $_value . "/" . $value;
      if ($index != $last_index) {
        $_breadcrumb_list .= "<li><a href='$_value'>" . t($value) . "</a></li>";
      } else {
        $_breadcrumb_list .= "<li active>" . t($value) . "</li>";
        break;
      }
    }

    return "<h4 class='page-title'>" . t($path[1]) . "</h4><ol class='breadcrumb text-right'>" . $_breadcrumb_list . "</ol>";

  }
}

?>
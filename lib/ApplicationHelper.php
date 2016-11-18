<?php

class ApplicationHelper {

  public static function extract() {

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

    // LOCALES

    function t($word) {
      return $_SESSION["i18n"]->$word;
    }

    // LAYOUT and TEMPLATE
    // for app/views/VIEW/ACTION.php and app/views/layouts/VIEW_layout.php

    function link_to($text, $link) {
      return "<a href='" . $link . "'>" . $text . "</a>";
    }

  }
}

?>
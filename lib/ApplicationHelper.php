<?php

class ApplicationHelper {

  public static function extract() {


    function scope() {

      // İzin verilmiş route'ları routes'a yükle
      $permitted_packages = func_get_args();
      $path = $permitted_packages[0];

      $permitted_packages = array_slice($permitted_packages, 1);

      $routes = [];
      foreach ($permitted_packages as $permitted_package) {
        foreach ($permitted_package as $permitted_route) {
          $permitted_route->_path = $path;
          $permitted_route->_rule = "/$path" . $permitted_route->_rule;
          $routes[] = $permitted_route;
        }
      }

      return $routes;
    }

    function resource($table, $path = null) {
      return [
        new ApplicationRoute("get",  "$table/index",   false, false, $path),  // all record
        new ApplicationRoute("get",  "$table/new",     false, false, $path),  // new record form
        new ApplicationRoute("post", "$table/create",  false, false, $path),  // new record create
        new ApplicationRoute("get",  "$table/show/",   false, false, $path),  // display record
        new ApplicationRoute("get",  "$table/edit/",   false, false, $path),  // edit record
        new ApplicationRoute("post", "$table/update",  false, false, $path),  // update record
        new ApplicationRoute("post", "$table/destroy", false, false, $path)   // destroy record
        ];
      }

      function resources($table, $path = null) {
        return [
      new ApplicationRoute("get",  "$table/index",    false, false, $path), // all record
      new ApplicationRoute("get",  "$table/new",      false, false, $path), // new record form
      new ApplicationRoute("post", "$table/create",   false, false, $path), // new record create
      new ApplicationRoute("get",  "$table/show/:id", false, true,  $path), // display record
      new ApplicationRoute("get",  "$table/edit/:id", false, true,  $path), // edit record
      new ApplicationRoute("post", "$table/update",   false, false, $path), // update record
      new ApplicationRoute("post", "$table/destroy",  false, false, $path)  // destroy record
      ];
    }

    function include_dynamical_segment($rule) {
      return strpos($rule, ":") ? true : false;
    }

    function post($rule, $target = false) {
      return new ApplicationRoute("post", $rule, $target, include_dynamical_segment($rule));
    }

    function get($rule, $target = false) {
      return new ApplicationRoute("get", $rule, $target, include_dynamical_segment($rule));
    }

    // LOCALES

    function t($_word) {
      $words = explode(".", $_word);
      $t_word = "";
      foreach ($words as $word) {
        $t_word = ($t_word == "") ? $_SESSION["i18n"]->$word : $t_word[$word];
      }

      return $t_word;
    }

    // LAYOUT and TEMPLATE
    // for app/views/VIEW/ACTION.php and app/views/layouts/VIEW_layout.php

    function render($filename, $dir = null) {
      if (is_null($dir)) {
        $_request_uri_list = explode("/", $_SERVER["REQUEST_URI"]);
        $dir = $_request_uri_list[1];
      }
      $file = getcwd(). "/app/views/$dir/_". "$filename.php";
      if (!file_exists($file)) {
        throw new FileNotFoundException("İçerik dosyası mevcut değil", $file);
      }
      include $file;
    }

    function link_to($text, $link) {
      return "<a href='" . $link . "'>" . $text . "</a>";
    }

  }
}

?>
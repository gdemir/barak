<?php
class ApplicationRoutes {
  public $tasks;
  public function dispatch($request_uri) {

    $route_draws = $this->tasks;
    $match = FALSE;
    foreach ($route_draws as $route_draw) {
      if ($route_draw[0] == $request_uri) {
        $target = explode("#", $route_draw[1]);
        $controller = $target[0];
        $action = $target[1];
        $layout = $controller;
        $view = $controller;
        $match = TRUE;
        break;
      }
    }
    if ($match) {
      $class_controller =  ucwords($controller) . 'Controller';

      // run controller class and before_filter functions
      $class = new $class_controller;

      $class->run($action);

      $vars = get_object_vars($class);

      // if ($vars["_redirect_to"]) {

      //   return header($vars["_redirect_to"], true, 302);

      // } else {

      $render = $vars["_render"];

      $render["layout"] = $render["layout"] ? $render["layout"] : $controller;
      $render["action"] = $render["action"] ? $render["action"] : $action;
      $render["view"] = $render["view"] ? $render["view"] : $controller;

      $layout_path = "app/views/layouts/" . $render["layout"] . "_layout.php";
      $view_path = "app/views/" . $render["view"] . "/" . $render["action"] . ".php";

      if (file_exists($layout_path)) {
        $layout_content = file_get_contents($layout_path);
      } else
      die("Layout mevcut değil" . $layout_path);

      if (file_exists($view_path)) {
        $view_content = file_get_contents($view_path);
      } else
      die("View path mevcut değil" . $view_path);

        // merge layout with view content
      $page_content = str_replace("{yield}", $view_content, $layout_content);

      $filename = 'tmp/' . time() . '.php';
      $file = fopen($filename, 'a');
      fwrite($file, $page_content);
      fclose($file);

      if (isset($vars["_params"])) {
        foreach ($vars["_params"] as $param => $value ) {
          $$param = $value;
        }
      }

      include $filename;

      unlink($filename);
//      }

    } else
    die("Böyle bir yönlendirme mevcut değil!");
  }
  public function draw() {
    $this->tasks = func_get_args();
  }
}
?>
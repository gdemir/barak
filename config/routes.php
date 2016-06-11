<?php
$routes = new Routes();
# TODO get/post

$routes->draw(
  ["", "home#index"],
  ["/", "home#index"]
  );

class Routes {
  public $tasks;
  public function dispatch($request_uri) {

    $route_draws = $this->tasks;
    $match = FALSE;
    foreach ($route_draws as $route_draw) {
      echo "X" .$route_draw[0] . "X - X" . $request_uri."X<br/>";
      if ($route_draw[0] == $request_uri) {
        $target = explode("#", $route_draw[1]);
        $controller = $target[0];
        $action = $target[1];
        $match = TRUE;
        break;
      }
    }
    if ($match) {
      $class_controller =  ucwords($controller) . 'Controller';
      echo $class_controller;
      // run controller class and before_filter functions
      $class = new $class_controller;
      $class->run($action);
      $vars = get_object_vars($class);

      print_r($vars);

      $layout_path = "app/views/layouts/" . $controller . "_layout.php";
      $view_path = "app/views/" . $controller . "/" . $action . ".php";

      if (file_exists($layout_path)) {
        $layout_content = file_get_contents($layout_path);
        //var_dump($layout);
        echo "evetttttttttttt";
      } else
      die("Layout mevcut değil");

      if (file_exists($view_path)) {
        $view_content = file_get_contents($view_path);
      } else
      die("View path mevcut değil");

      // merge layout with view content
      $page_content = str_replace("{yield}", $view_content, $layout_content);

      $filename = 'tmp/' . time() . '.php';

      $file = fopen($filename, 'a');
      fwrite($file, $page_content);
      fclose($file);

      foreach( $vars["_params"] as $param => $value ) {
        $$param = $value;
      }

      include $filename;

      unlink($filename);

    } else
    die("Böyle bir yönlendirme mevcut değil!");
  }
  public function draw() {
    $this->tasks = func_get_args();
  }
}
?>

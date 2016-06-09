<?php
$routes = new Routes();
# TODO get/post

$routes->draw(
  ["", "home#index", ["except" => []]],
  ["/", "home#index", []]
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
     } else
       die("Böyle bir yönlendirme mevcut değil!");
  }
  public function draw() {
    $this->tasks = func_get_args();
  }
}
?>

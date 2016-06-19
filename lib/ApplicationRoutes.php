<?php

class ApplicationRoutes {
  public $routes = [];
  public $request_route;

  public function __construct() {
    //trim(trim($_SERVER['REQUEST_URI']), "/")
    //echo $_SERVER["REQUEST_METHOD"].".". $_SERVER['REQUEST_URI'];
    $this->request_route = new Route($_SERVER["REQUEST_METHOD"], $_SERVER['REQUEST_URI'], false);
  }

  public static function dispatch($route) {
  	print_r($route);

    // run controller class and before_filter functions
    $class = new $route->controller();

    $class->run($route->action);

    // for render page

    $vars = get_object_vars($class);

    $render = $vars["_render"];

    $render["layout"] = $render["layout"] ? $render["layout"] : $route->layout;
    $render["action"] = $render["action"] ? $render["action"] : $route->action;
    $render["view"] = $render["view"] ? $render["view"] : $route->view;

    $layout_path = "app/views/layouts/" . $render["layout"] . ".php";
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

  }

  public static function post($rule, $target = false) {
    return new Route("post", $rule, $target);
  }

  public static function get($rule, $target = false) {
    return new Route("get", $rule, $target);
  }

  public static function draw() {
    $r = new ApplicationRoutes();

    // İzin verilmiş route'ları routes'a yükle
    $permitted_routes = func_get_args();
    foreach ($permitted_routes as $permitted_route) {

      if (array_key_exists($permitted_route->rule, $r->routes))
        die("Bu yönlendirme daha önceden tanımlanmış!: →" . $permitted_route->rule . "←");

      $r->routes[$permitted_route->rule] = $permitted_route;
    }

    // İstek url ile routes'ı içinden bul ve sevk et
    if (array_key_exists($r->request_route->rule, $r->routes)) {
      $route = $r->routes[$r->request_route->rule];
      ApplicationRoutes::dispatch($route);

    } else
    die("Böyle bir yönlendirme mevcut değil!");
  }
}



class Route {

  private $is_correct_route = false;

  public $rule;
  public $controller;
  public $layout;
  public $action;
  public $view;

  function __construct($method, $rule, $target = false) {

    $this->rule = $rule;
    $this->method = $method;

    if ($target) {
      $rule = explode("#", $target);
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } elseif (strpos($rule, "/") !== false) {
      $rule = explode("/", $rule);
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } else
    die("/config/routes.php içinde beklenmedik kurallar!: →" . $rule . "←");
  }

  private function set($controller, $view, $layout, $action) {
    $this->controller = ucwords($controller) . 'Controller';
    $this->view = $view;
    $this->layout = $layout . "_layout";
    $this->action = $action;
    $this->is_correct_route = true;
  }
}
?>
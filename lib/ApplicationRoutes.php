<?php
class ApplicationRoutes {
  private $_routes = [];
  private $_request_route;

  public function __construct() {
    $this->_request_route = new ApplicationRoute($_SERVER["REQUEST_METHOD"], $_SERVER['REQUEST_URI'], false);
  }

  public static function draw() {
    $r = new ApplicationRoutes();

    // İzin verilmiş route'ları routes'a yükle
    $permitted_routes = func_get_args();
    foreach ($permitted_routes as $permitted_route) {
      if (is_array($permitted_route)) { // for ApplicationRoutes::resource();

        foreach ($permitted_route as $permitted_r)
          $r->{$permitted_r->_rule} = $permitted_r;
      } else
      $r->{$permitted_route->_rule} = $permitted_route;

    }
    foreach ($r->_routes as $route) {
      print_r($route);
      echo "<br/>";
    }

    // İstek url ile routes'ı içinden bul ve sevk et
    $route = $r->{$r->_request_route->_rule};
    $route->run();
  }

  public function __get($route) {
    if (array_key_exists($this->_request_route->_rule, $this->_routes)) {
      return $this->_routes[$route];
    } else {
      die("Böyle bir yönlendirme mevcut değil!: →" . $this->_request_route->_rule . "←");
    }
  }

  public function __set($route_rule, $route) {
    if (array_key_exists($route_rule, $this->_routes))
      die("Bu yönlendirme daha önceden tanımlanmış!: →" . $route_rule . "←");
    $this->_routes[$route_rule] = $route;
  }
}
?>
<?php
class ApplicationRoutes {
  private $_routes = [ "GET" => [], "POST" => [] ]; // http put, delete is not support
//  private $_request_route;

  // public function __construct() {
  //   $this->_request_route = new ApplicationRoute($_SERVER["REQUEST_METHOD"], $_SERVER['REQUEST_URI'], false);
  // }

  public static function draw() {
    $request_route = new ApplicationRoute($_SERVER["REQUEST_METHOD"], $_SERVER['REQUEST_URI'], false);

    $r = new ApplicationRoutes();

    // İzin verilmiş route'ları routes'a yükle
    $permitted_routes = func_get_args();
    foreach ($permitted_routes as $permitted_route) {

      if (is_array($permitted_route)) { // for ApplicationRoutes::resource();
        foreach ($permitted_route as $permitted_r)
          $r->{$permitted_r->_method} = $permitted_r;
      } else {
        $r->{$permitted_route->_method} = $permitted_route;
      }

    }

    // // TEST $route list
    //print_r($r->_routes);
    foreach ($r->_routes as $method => $routes) {
      echo "<br/>";
      print_r($method);
      echo "<br/>";

      foreach ($routes as $route) {
      	echo "<br/>";
        print_r($route);
        echo "<br/>";
      }
    }

    // İstek url ile routes'ı içinden bul ve sevk et
    $route = $r->get_route($request_route);

    $route->run();
  }

  public function get_route(ApplicationRoute $request_route) {

    if (array_key_exists($request_route->_method, $this->_routes)) {
      if (array_key_exists($request_route->_rule, $this->_routes[$request_route->_method])) {
        return $this->_routes[$request_route->_method][$request_route->_rule];
      } else { // search for match routes

        foreach ($this->_routes[$request_route->_method] as $_route) {
          if ($_route->_match) {

            $request_rule = explode("/", trim($request_route->_rule, "/"));
            $permit_rule = explode("/", trim($_route->_rule, "/"));

            if (count($request_rule) == count($permit_rule)) {
              $match = true;
              foreach ($request_rule as $index => $value) {
                if (($request_rule[$index] != $permit_rule[$index]) and ($permit_rule[$index] != ApplicationRoute::dynamical_segment)) {
                  $match = false;
                  break;
                }
             }
              if ($match) {
                $permit_match_rule = explode("/", trim($_route->_match_rule, "/"));
                preg_match_all('@:([\w]+)@', $_route->_match_rule, $segments, PREG_PATTERN_ORDER);
                $segments = $segments[0];

                // get methodları için params'a yükle
                foreach ($segments as $segment) {
                  if ($index = array_search($segment, $permit_match_rule))
                    $_route->_params[substr($segment, 1)] = $request_rule[$index];
                }

                $_route->set($request_rule[0], $request_rule[0], $request_rule[0], $request_rule[1]);
                return $_route;
              }
            }
          }
        }
      }
      throw new ConfigurationException("Böyle bir yönlendirme mevcut değil", $request_route->_rule);
    }
    throw new ConfigurationException("Uzay çağında bizim henüz desteklemediğimiz bir method", $request_route->_method);
  }

  public function __set($route_method, $route) {
    if (array_key_exists($route->_rule, $this->_routes[$route_method]))
      throw new ConfigurationException("Bu yönlendirme daha önceden tanımlanmış", $route->_rule);
    $this->_routes[$route_method][$route->_rule] = $route;
  }
}
?>

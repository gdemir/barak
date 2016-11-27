<?php

class ApplicationRoute {

  const dynamical_segment = "_dynamical_segment_"; // change name for :id/:action
  const default_controller = "default";
  const default_action = "index";

  public $_params = [];
  public $_path;
  public $_match;
  public $_match_rule;
  public $_method;
  public $_rule;

  public $_controller;
  public $_action;

  public function __construct($method, $rule, $target = false, $match = false, $path = null) {
    $this->_path = ($path) ? "/$path" : "";

    if ($match) {

      if ($target) // TODO Rewrite
        $option = explode("#", trim($target, "/"));
      else
        $option = explode("/", trim($rule, "/"));

      self::set($method, $match, $this->_path . $rule, preg_replace("|:[\w]+|", self::dynamical_segment, $rule), $option[0], $option[1]);

    } elseif ($target) {

      $option = explode("#", trim($target, "/"));
      self::set($method, $match, "", $this->_path . $rule, $option[0], $option[1]);

    } elseif (strpos($rule, "/") !== false) { // dizgi içerisinde konum(indexi) yok değilse (yani varsa)

      $option = explode("/", trim($rule, "/"));
      if (count($option) == 2) {  // /home/index

        self::set($method, $match, "", $this->_path . $rule, $option[0], $option[1]);

      } elseif (count($option) == 3) { // scope parser

        $this->_path = $option[0];
        self::set($method, $match, "", "/$this->_path/" . $option[1] . "/". $option[2], $option[1], $option[2]);

      } else {
        $option = explode("/", trim($rule, "/"));// echo "php ya da web sunucu otomatik boş istek yolluyor TODO";

        self::set(
          $method, $match, "", $rule,
          self::default_controller, self::default_action
          );
      }
    } else {
      throw new ConfigurationException("/config/routes.php içinde beklenmedik kurallar", $rule);
    }
  }

  public function set($method, $match, $match_rule, $rule, $controller, $action) {
    $this->_method = strtoupper($method);
    $this->_match = $match;
    $this->_match_rule = $match_rule;
    $this->_rule = $rule;

    $this->_controller = $controller;
    $this->_action = $action;
  }

  public function run() {

    // unset($GLOBALS["success"]); unset($GLOBALS["danger"]); // TODO

    // run controller class and before_filter functions
    $controller_class = ucwords($this->_controller) . 'Controller';
    if (!class_exists($controller_class))
      throw new FileNotFoundException("Controller sınıfı/dosyası yüklenemedi", $controller_class);

    // translate for i18n
    if (isset($_SESSION["i18n"])) $_SESSION["i18n"]->run();

    $c = new $controller_class();

    // router'in paramslarını(sayfadan :id, çekmek için), controller'dan gelen paramslara yükle
    $c->_params = $this->_params;
    $c->run($this->_action);

    // controller var fetch
    $vars = get_object_vars($c);

    // controllerın renderi
    // vars["_render"];

    // render controller choice
    $v = new ApplicationView();

    if ($this->_path) { // have scope or path of resouce/resouces
      $v->set(["layout" => $this->_path]);
      $v->set(["view" => $this->_path . "/" . $this->_controller, "action" => $this->_action]);
    } else {
      $v->set(["view" => $this->_controller, "action" => $this->_action]);
    }

    if ($vars["_render"])
      $v->set($vars["_render"]);

    // controllerin paramsları
    // $vars["_params"];

    $v->run($vars["_params"]);
  }
}

?>
<?php

class ApplicationRoute {

  const dynamical_segment = "_dynamical_segment_"; // change name for :id/:action
  const default_controller = "default";
  const default_action = "index";

  public $_params = [];
  public $_match;
  public $_match_rule;
  public $_method;
  public $_rule;

  public $_controller;
  public $_action;

  public function __construct($method, $rule, $target = false, $match = false) {
    if ($match) {
      self::set(
        $method, $match, $rule, preg_replace("|:[\w]+|", self::dynamical_segment, $rule),
        self::default_controller, self::default_action
        );
    } elseif ($target) {
      $target_option = explode("#", $target);
      self::set(
        $method, $match, "", $rule,
        $target_option[0], $target_option[1]
        );
    } elseif (strpos($rule, "/") !== false) { // dizgi içerisinde konum(indexi) yok değilse (yani varsa)
      $target_option = explode("/", trim($rule, "/"));
      if (count($target_option) == 2)
        self::set(
          $method, $match, "", $rule,
          $target_option[0], $target_option[1]
          );
      else
        self::set(
          $method, $match, "", $rule,
          self::default_controller, self::default_action
          );
    } else
    throw new ConfigurationException("/config/routes.php içinde beklenmedik kurallar", $rule);
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
    $c->run($this->_action);

    // controller var fetch
    $vars = get_object_vars($c);

    // controllerın renderi
    // vars["_render"];

    // render controller choice
    $v = new ApplicationView();
    $v->render(["view" => $this->_controller, "action" => $this->_action]);

    if ($vars["_render"])
      $v->render($vars["_render"]);

    // controllerin paramsları
    // $vars["_params"];

    // router'in paramslarını(sayfadan :id, çekmek için), controller'dan gelen paramslara yükle
    // $vars["_params"]["params"] = $this->_params;

    $v->run($vars["_params"]);
  }
}

?>
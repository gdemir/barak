<?php
class ApplicationRoute {

  const dynamical_segment = "_dynamical_segment_"; // change name for :id/:action

  const default_controller = "default";
  const default_layout = "default";
  const default_view = "default";
  const default_action = "index";

  public $_params = [];

  public $_match;
  public $_match_rule;

  public $_method;
  public $_rule;

  public $_controller;
  public $_layout;
  public $_action;
  public $_view;

  public function __construct($method, $rule, $target = false, $match = false) {

    if ($match) {
      self::set(
        $method, $match, $rule, preg_replace("|:[\w]+|", self::dynamical_segment, $rule),
        self::default_controller, self::default_view, self::default_layout, self::default_action
        );
    } elseif ($target) {
      $target_option = explode("#", $target);
      self::set(
        $method, $match, "", $rule,
        $target_option[0], $target_option[0], $target_option[0], $target_option[1]
        );
    } elseif (strpos($rule, "/") !== false) { // dizgi içerisinde konum(indexi) yok değilse (yani varsa)
      $target_option = explode("/", trim($rule, "/"));
      if (count($target_option) == 2)
        self::set(
          $method, $match, "", $rule,
          $target_option[0], $target_option[0], $target_option[0], $target_option[1]
          );
      else
        self::set(
          $method, $match, "", $rule,
          self::default_controller, self::default_view, self::default_layout, self::default_action
          );
    } else
    throw new ConfigurationException("/config/routes.php içinde beklenmedik kurallar", $rule);
  }

  public function set($method, $match, $match_rule, $rule, $controller, $view, $layout, $action) {
    $this->_method = strtoupper($method);
    $this->_match = $match;
    $this->_match_rule = $match_rule;
    $this->_rule = $rule;

    $this->_controller = ucwords($controller) . 'Controller';
    $this->_view = $view;
    $this->_layout = $layout . "_layout";
    $this->_action = $action;
  }

  public function run() {

    // run controller class and before_filter functions
    if (!class_exists($this->_controller))
      throw new FileNotFoundException("Controller sınıfı/dosyası yüklenemedi", $this->_controller);

    $class = new $this->_controller();

    $class->run($this->_action);

    // controller var fetch
    $vars = get_object_vars($class);

    // controllerın renderi
    // vars["_render"]

    // render controller choice
    $render = $vars["_render"];

    if (is_array(($vars["_render"]))) {
      if (array_key_exists("layout", $vars["_render"]))
        $render["layout"] .= "_layout";
    } elseif (is_string($vars["_render"])) {
      $url = explode("/", trim($vars["_render"], "/"));

      if (isset($url[1])) {
        $render["action"] = $url[1];
        $render["view"] = $url[0];
      } else {
        $render["action"] = $url[0];
        $render["view"] = null;
      }

      $render["layout"] = null;
    }

    // default assignment on route of congfig/routes.php
    $render["layout"] = $render["layout"] ?? $this->_layout;
    $render["action"] = $render["action"] ?? $this->_action;
    $render["view"]   = $render["view"]   ?? $this->_view;
    //print_r($render);

    // LAYOUT
    $layout_path = "app/views/layouts/" . $render["layout"] . ".php";

    if (file_exists($layout_path)) {
      $layout_content = file_get_contents($layout_path);
    } else {
      throw new FileNotFoundException("Layout path mevcut değil", $layout_path);
    }

    // VIEW
    $view_path = "app/views/" . $render["view"] . "/" . $render["action"] . ".php";
    if (file_exists($view_path)) {
      $view_content = file_get_contents($view_path);
    } else {
      throw new FileNotFoundException("View path mevcut değil", $view_path);
    }

    // merge LAYOUT content
    $page_content = (isset($layout_content)) ? str_replace("{yield}", $view_content, $layout_content) : $view_content;

    // controllerin paramsları
    // $vars["_params"]

    // router'in paramslarını(sayfadan :id, çekmek için), controller'dan gelen paramslara yükle
    $vars["_params"]["params"] = $this->_params;

    self::display($page_content, $vars["_params"]);
  }

  public function display($content, $params) {

    // controller'in paramslarını yükle
    foreach ($params as $param => $value) {
      $$param = $value;
    }

    // http://stackoverflow.com/questions/1184628/php-equivalent-of-include-using-eval)
    eval("?> $content <?php");

    unset($content);
  }
}
?>

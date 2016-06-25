<?php
class ApplicationRoute {

  const dynamical_segment = "_dynamical_segment_";

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

    $this->_match = $match;
    $this->_method = $method;

    if ($match) {
      $this->_rule = preg_replace("|:[\w]+|", self::dynamical_segment, $rule);
      $this->_match_rule = $rule;
      self::set(self::default_controller, self::default_view, self::default_layout, self::default_action);
    } elseif ($target) {
      $this->_rule = $rule;
      $this->_match_rule = "";
      $rule = explode("#", $target);
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } elseif (strpos($rule, "/") !== false) { // dizgi içerisinde konum(indexi) yok değilse (yani varsa)
      $this->_rule = $rule;
      $this->_match_rule = "";
      $rule = explode("/", trim($this->_rule, "/"));
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } else
    die("/config/routes.php içinde beklenmedik kurallar!: →" . $rule . "←");
  }

  public function set($controller, $view, $layout, $action) {
    $this->_controller = ucwords($controller) . 'Controller';
    $this->_view = $view;
    $this->_layout = $layout . "_layout";
    $this->_action = $action;
  }

  public function run() {

    // run controller class and before_filter functions
    if (!class_exists($this->_controller))
      die("Controller dosyası yüklenemedi!:" . $this->_controller);

    $class = new $this->_controller();

    $class->run($this->_action);

    // for render page

    $vars = get_object_vars($class);

    $render = $vars["_render"];

    $render["layout"] = $render["layout"] ?? $this->_layout;
    $render["action"] = $render["action"] ?? $this->_action;
    $render["view"] = $render["view"] ?? $this->_view;

    $layout_path = "app/views/layouts/" . $render["layout"] . ".php";
    $view_path = "app/views/" . $render["view"] . "/" . $render["action"] . ".php";

    if (file_exists($layout_path)) {
      $layout_content = file_get_contents($layout_path);
    }

    if (file_exists($view_path)) {
      $view_content = file_get_contents($view_path);
    } else {
      die("View path mevcut değil!: →" . $view_path . "←");
    }

    // merge layout with view content
    $page_content = (isset($layout_content)) ? str_replace("{yield}", $view_content, $layout_content) : $view_content;

    $filename = 'tmp/' . time() . '.php';
    $file = fopen($filename, 'a');
    fwrite($file, $page_content);
    fclose($file);


    // router'in paramslarını yükle
    foreach ($this->_params as $param => $value) {
      $$param = $value;
    }

    // controller'in paramslarını yükle
    if (isset($vars["_params"])) {
      foreach ($vars["_params"] as $param => $value) {
        $$param = $value;
      }
    }

    include $filename;

    unlink($filename);
  }
}
?>
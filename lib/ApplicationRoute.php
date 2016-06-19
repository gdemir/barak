<?php
class ApplicationRoute {

  public $_method;
  public $_rule;
  public $_controller;
  public $_layout;
  public $_action;
  public $_view;

  public function __construct($method, $rule, $target = false) {

    $this->_rule = $rule;
    $this->_method = $method;

    if ($target) {
      $rule = explode("#", $target);
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } elseif (strpos($this->_rule, "/") !== false) {
      $rule = explode("/", trim($this->_rule, "/"));
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } else
    die("/config/routes.php içinde beklenmedik kurallar!: →" . $rule . "←");
  }

  private function set($controller, $view, $layout, $action) {
    $this->_controller = ucwords($controller) . 'Controller';
    $this->_view = $view;
    $this->_layout = $layout . "_layout";
    $this->_action = $action;
  }

  public function run() {

    // run controller class and before_filter functions
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
    } else
    die("View path mevcut değil!: →" . $view_path . "←");

    // merge layout with view content
    $page_content = (isset($layout_content)) ? str_replace("{yield}", $view_content, $layout_content) : $view_content;

    $filename = 'tmp/' . time() . '.php';
    $file = fopen($filename, 'a');
    fwrite($file, $page_content);
    fclose($file);

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
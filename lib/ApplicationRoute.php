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

    self::set(self::default_controller, self::default_view, self::default_layout, self::default_action);
    $this->_match = $match;
    $this->_method = strtoupper($method);

    if ($match) {
      $this->_rule = preg_replace("|:[\w]+|", self::dynamical_segment, $rule);
      $this->_match_rule = $rule;
    } elseif ($target) {
      $this->_rule = $rule;
      $this->_match_rule = "";
      $rule = explode("#", $target);
      self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } elseif (strpos($rule, "/") !== false) { // dizgi içerisinde konum(indexi) yok değilse (yani varsa)
      $this->_rule = $rule;
      $this->_match_rule = "";
      $rule = explode("/", trim($this->_rule, "/"));
      if (count($rule) == 2)
        self::set($rule[0], $rule[0], $rule[0], $rule[1]);
    } else
    throw new ConfigurationException("/config/routes.php içinde beklenmedik kurallar", $rule);
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
      throw new FileNotFoundException("Controller sınıfı/dosyası yüklenemedi", $this->_controller);

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
      throw new FileNotFoundException("View path mevcut değil", $view_path);
    }

    // merge layout with view content
    $page_content = (isset($layout_content)) ? str_replace("{yield}", $view_content, $layout_content) : $view_content;

    // http://www.php.net/manual/fr/ref.outcontrol.php
    /*
      ob_start();
      eval(file_get_contents($file));
      $result = ob_get_contents();
      ob_end_clean();
    */

    // controller'in paramsları
    // $vars["_params"]

    // router'in paramslarını(sayfadan :id, çekmek için), controller'dan gelen paramslara yükle
    $vars["_params"]["params"] = $this->_params;

    self::display($page_content, $vars["_params"]);
  }

  public function display($content, $params) {

    $filename = 'tmp/' . time() . '.php';

    if (!($fp = fopen($filename, 'a')))
      throw new FileNotFoundException("Sayfayı görüntülemek için geçici dosya oluşturulamadı", $filename);

    fwrite($fp, $content);
    fclose($fp);

    unset($content);
    unset($fp);

    // controller'in paramslarını yükle
    foreach ($params as $param => $value) {
      $$param = $value;
    }

    include $filename;

    unlink($filename);
  }
}
?>

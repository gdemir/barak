<?php
class ApplicationController {

  public $_params;

  public $_render = [
  "layout" => "",
  "view" => "",
  "action" => ""
  ];

  public function index() {
    echo "Application#index";
  }

  public function _filter($action, $filter_actions) {
    echo "<br/>Çocuk class için geldik, öncelik için bir şeyler yapacağız<br/>";

    foreach ($filter_actions as $filter_action) {

      if (array_key_exists("name", $filter_action)) {
        if (method_exists($this, $filter_action["name"])) {
          if (array_key_exists("only", $filter_action)) {
            if (in_array($action, $filter_action["only"]))
              echo $this->{$filter_action["name"]}();
          } elseif (array_key_exists("except", $filter_action)) {
            if (!in_array($action, $filter_action["except"]))
              echo $this->{$filter_action["name"]}();
          } elseif (!array_key_exists("only", $filter_action) and !array_key_exists("except", $filter_action)) {
            echo $this->{$filter_action["name"]}();
          }
        }
      }

    }
  }

  public function run($action) {
    $this->_action = $action;

    if (isset($this->before_actions)) $this->_filter($action, $this->before_actions);

    if (method_exists($this, $action)) $this->$action();

    if (isset($this->after_actions)) $this->_filter($action, $this->after_actions);
  }

  public function render($option) {
    if (is_array($option)) {

      $this->_render["layout"] = isset($option["layout"]) ? $option["layout"] : null;
      $this->_render["view"] = isset($option["view"]) ? $option["view"] : null;
      $this->_render["action"] = isset($option["action"]) ? $option["action"] : null;

    } else {
      $url = explode("/", trim($option, "/"));

      if (isset($url[1])) {
        $this->_render["action"] = $url[1];
        $this->_render["view"] = $url[0];
      } else {
        $this->_render["action"] = $url[0];
        $this->_render["view"] = null;
      }

      $this->_render["layout"] = null;
    }
  }

  public function redirect_to($url) {
    $url = trim($url, "/");
    $redirect_to = "Location : http://" . $_SERVER['SERVER_NAME'] . "/" . $url;
    exit(header($redirect_to, false, 303));
  }

  public function __get($param) {
    return $this->_params[$param];
  }

  public function __set($param, $value) {
    $this->_params[$param] = $value;
  }
}
?>

<?php
class ApplicationController {

  public $_params = [];
  public $_render = [];
  //private $_render_struct_keys = ["layout", "view", "action"];

  public function _filter($action, $filter_actions) {
    echo "<br/>Çocuk class için geldik, öncelik için bir şeyler yapacağız<br/>";

    foreach ($filter_actions as $filter_action) {

      if (array_key_exists(0, $filter_action)) {
      	$filter_action_name = $filter_action[0];
        if (method_exists($this, $filter_action_name)) {
          if (array_key_exists("only", $filter_action)) {
            if (in_array($action, $filter_action["only"]))
              echo $this->{$filter_action_name}();
          } elseif (array_key_exists("except", $filter_action)) {
            if (!in_array($action, $filter_action["except"]))
              echo $this->{$filter_action_name}();
          } elseif (!array_key_exists("only", $filter_action) and !array_key_exists("except", $filter_action)) {
            echo $this->{$filter_action_name}();
          }
        }
      }

    }
  }

  public function run($action) {
    if (isset($this->before_actions)) $this->_filter($action, $this->before_actions);

    if (method_exists($this, $action)) $this->$action();

    if (isset($this->after_actions)) $this->_filter($action, $this->after_actions);
  }

  public function render($option) {
    if (is_array($option)) {
      $this->_render = $option;
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
    exit(header("Location : http://" . $_SERVER['SERVER_NAME'] . "/" . trim($url, "/"), false, 303));
  }

  public function __get($param) {
    return $this->_params[$param];
  }

  public function __set($param, $value) {
    $this->_params[$param] = $value;
  }
}
?>

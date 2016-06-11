<?php
class ApplicationController {
  public $_params;

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
    if (method_exists($this, $action)) {
      if (isset($this->before_actions)) $this->_filter($action, $this->before_actions);
      $this->$action();
      if (isset($this->after_actions)) $this->_filter($action, $this->after_actions);

    } else
      die(get_class($this) . " içerisinde ". $action . " fonksiyonuna sahip değil<br/>");
  }

  public function __get($param) {
    return $this->_params[$param];
  }

  public function __set($param, $value) {
    $this->_params[$param] = $value;
  }
}
?>
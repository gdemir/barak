<?php
class ApplicationController {

  public function index() {
    echo "Application#index";
  }

  public function before_filter() {
    echo "<br/>Çocuk class için geldik, öncelik için bir şeyler yapacağız<br/>";
    $action = $this->filter;
    if (method_exists($this, $action)) {
      $this->$action();
    }
  }

}
?>

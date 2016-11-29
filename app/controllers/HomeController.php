<?php

class HomeController extends ApplicationController {

  public function home() {
    $this->redirect_to("/home/index");
  }

  // public function index() {}

}

?>
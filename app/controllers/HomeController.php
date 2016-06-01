<?php
class HomeController extends ApplicationController {
  protected $filter = "login"; #TODO make array
  public function index () {
    echo "Merhaba home#index<br/>";
  }
  public function login() {
    echo "Her işlem öncesi login oluyoruz";
  }  
}
?>

<?php
class HomeController extends ApplicationController {
  protected $filter = "login";
  public function index () {
    echo "Merhaba home#index<br/>";
  }
  public function login() {
    echo "Her işlem öncesi login oluyoruz";
  }  
}
?>

<?php
class HomeController extends ApplicationController {

  // protected $layout = ["home", "except" => ["login", "index"]]; // TODO

  protected $before_actions = [
  ["login", "except" => ["login", "index"]],
  ["notice_clear", "only" => ["index"]],
  ["every_time"]
  ];

  public function index() {
  }
  public function login() {
    // echo "Her işlem öncesi login oluyoruz<br/>";
  }
  public function every_time() {
    // echo "Home#every_time : her zaman çalışırım<br/>";
  }
  public function notice_clear() {
    // echo "Home#notice_clear : duyular silindi<br/>";
  }
  public function close() {
    // echo "Home#close : dükkan kapandı<br/>";
  }
  public function dashboard() {
    // echo "dashboard öncesi olaylar olaylar<br/>";
  }
}
?>
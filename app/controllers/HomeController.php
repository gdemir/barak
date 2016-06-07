<?php
class HomeController extends ApplicationController {

  protected $before_actions = [
                                  ["name" => "login", "except" => ["login", "index"]],
                                  ["name" => "notice_clear", "only" => ["index"]],
                                  ["name" => "every_time"]
                              ];

  public function index() {
    echo "Merhaba home#index<br/>";
    // Model function tests
    $user = new Users();
    $user->first_name ="Gökhan";

    //$user->tc = 123123;
    echo $user->first_name;
    $user->save();

    print_r(Users::primary_key());
    print_r(Comments::primary_key());
    print_r(Users::find(1));

    Users::update(1, array("first_name" => "hmm3", "last_name" => "hmm4"));
		echo "<br/>";

		print_r(Users::first());
		print_r(Users::last());
		echo "<br/>";

		print_r(Users::all());
		echo "<br/>";

		print_r(Users::fieldnames());
		print_r(Comments::fieldnames());
		echo "<br/>";
  }
  public function login() {
    echo "Her işlem öncesi login oluyoruz<br/>";
  }
  public function every_time() {
    echo "Home#every_time : her zaman çalışırım<br/>";
  }
  public function notice_clear() {
    echo "Home#notice_clear : duyular silindi<br/>";
  }
  public function close() {
    echo "Home#close : dükkan kapandı<br/>";
  }
}
?>

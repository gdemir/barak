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
    $user = new User();
    print_r($user);
    $user->first_name ="GökhanX";

    //$user->tc = 123123;
    echo $user->first_name;
    $user->save();

  //   print_r(User::primary_keyname());
  //   print_r(Comments::primary_keyname());


  //   User::update(123342, array("first_name" => "hmm3", "last_name" => "hmm4"));
  //   echo "<br/>";
  //   echo "<br/>s";
  //   //print_r(User::where("first_name = 'GökhanX'"));
  //   echo "s<br/>";
		// echo "<br/>";

  //   print_r(User::first());
  //   print_r(User::last());
  //   echo "<br/>";
    echo ".<br/><br/><br/>";
    print_r(User::find_all([123827,123828,123829]));
    //Comment::joins("user");
    $this->users = User::all();



  //   echo "<br/>!!!!!!!!!!!!!!!!!!!!!!";
  //   //print_r($this->user);
  //   echo "<br/>!!!!!!!!!!!!!!!!!!!!!!";

  //   print_r(User::fieldnames());
  //   print_r(Comments::fieldnames());

  //   echo "<br/>xxxxxxxxxxxxxxxxxxxxx<br/>";

  //   User::delete_all(["first_name" => "GökhanX"]);
		//exit(header("Location: http://localhost/app/views/home/dashboard.php"));
		//return $this->redirect_to("/home/about");


    // $this->render("/home/index");
    // $this->render(["action" => "show"]);
    // $this->render(["layout" => "admin", "action" => "show"]);
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
  public function dashboard() {
  	echo "dashboard öncesi olaylar olaylar<br/>";
  }
}
?>



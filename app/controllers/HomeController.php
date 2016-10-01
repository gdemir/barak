<?php
class HomeController extends ApplicationController {

  protected $before_actions = [
  ["login", "except" => ["login", "index"]],
  ["notice_clear", "only" => ["index"]],
  ["every_time"]
  ];

  public function index() {
    echo "Merhaba home#index<br/>";
    // Model function tests
    $a = new User(["last_name" => "ddd"]);
    print_r($a);
    echo "zzzzzzzzzzzzzzzzzzzzzzzzzzzzzz" . "<br/>";
    print_r(User::load()->where(["last_name" => "ddd"])->where(["first_name" => "GökhanX"])->order("last_name", "desc")->get_all());
    echo "zzzzzzzzzzzzzzzzzzzzzzzzzzzzzz" . "<br/>";
    $user = new User();
    print_r($user);
    $user->first_name ="GökhanX22";

    //$user->tc = 123123;
    // if (User::load()->exists(1))
    //   echo "evet";
    // else
    //   echo "haryhir";
    echo $user->first_name;
    $user->save();

  // //   print_r(User::primary_keyname());
  // //   print_r(Comments::primary_keyname());


  // //   User::update(123342, array("first_name" => "hmm3", "last_name" => "hmm4"));
  // //   echo "<br/>";
  // //   echo "<br/>s";
  // //   //print_r(User::where("first_name = 'GökhanX'"));
  // //   echo "s<br/>";
  //   // echo "<br/>";

  // //   print_r(User::first());
  // //   print_r(User::last());
  // //   echo "<br/>";

    print_r(User::find_all([123827,123828,123829]));
     echo ">>><br/><br/><br/>";
    $this->users = User::load()->joins(["Comment", "Address"])->where(["User.id" => 2])->select("User.first_name, User.id")->get_all();

    echo ">>>><br/><br/><br/>";
//    $this->users = User::all();



  //   echo "<br/>!!!!!!!!!!!!!!!!!!!!!!";
  //   //print_r($this->user);
  //   echo "<br/>!!!!!!!!!!!!!!!!!!!!!!";

  //   print_r(User::fieldnames());
  //   print_r(Comments::fieldnames());

  //   echo "<br/>xxxxxxxxxxxxxxxxxxxxx<br/>";

  //   User::delete_all(["first_name" => "GökhanX"]);
    //exit(header("Location: http://localhost/app/views/home/dashboard.php"));
    //return $this->redirect_to("/home/about");


    //return $this->render(["layout" => false]);
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



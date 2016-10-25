<?php
class HomeController extends ApplicationController {

	protected $before_actions = [
	["login", "except" => ["login", "index"]],
	["notice_clear", "only" => ["index"]],
	["every_time"]
	];

	public function index() {
  //   echo "Merhaba home#index<br/>";


  //   // Model function tests
  //   echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br/>";
//$user = new User();
//$user->where(["id"=>1]);
  // 	$a = ApplicationSql::describe("User")->fetchAll(PDO::FETCH_ASSOC);;
  // 	print_r($a);
  // 	echo "<br/>";
  // 	echo "<br/>";

		$a = User::load()->pluck("id");

		$user = User::create(["first_name" => "Gökhan","last_name"=>"a","username"=>"b","password"=>"asd"]);


		print_r($user);
		echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br/>";
    // $users = User::load()->where(["id" => 1])->select("first_name")->take();
    // print_r($users);
    // echo "<br/><b>### select kullanıldı!</b><br/>";

    // print_r(User::load()->where(["last_name" => "ddd"])->where(["first_name" => "GökhanX"])->order("last_name", "desc")->take());

    // $user1 = new User(["last_name" => "Demir"]);
    // print_r($user1);
    // echo "<br/><b>### yeni obje alındı</b><br/>";
    // $user1->first_name ="Gökmen";
    // $user1->department_id = 4;
    // $user1->save();
    // print_r($user1);
    // echo "<br/><b>### yeni kullanıcı oluşturuldu!</b><br/>";

    // echo "<br/>id ->" . $user1->id . "<br/>";
    // $user2 = User::find($user1->id);
    // print_r($user2);
    // echo "<br/><b>### eski obje okundu</b><br/>";
    // $user2->last_name = "Bakır";
    // $user2->save();
    // print_r($user2);
    // echo "<br/><b>### eski kullanıcı güncellendi!</b><br/>";

    // $user3 = User::load()->where(["first_name" => $user1->first_name, "last_name" => "Bakır"])->select("first_name")->take();
    // print_r($user3);
    // echo "<br/><b>### select kullanıldı!</b><br/>";

  // //   User::update(645, array("first_name" => "hmm3", "last_name" => "hmm4"));
  // // // // //   echo "<br/>";
  // // // // //   echo "<br/>s";
  // //   print_r(User::load()->where(["first_name" => "Gökhan"])->take());
  // //   echo "<br/><b>### where kullanıldı!</b><br/>";

  // // //   // echo "<br/>";

  //   //print_r(User::load()->first()->take());
  // // // //   print_r(User::last());
  // // // //   echo "<br/>";

  // //   // print_r(User::find_all([123827,123828,123829]));

  // //   // echo User::exists(340) == null ? "evet" : "hayir";
  //   //$this->users = User::load()->joins(["Comment"])->where(["User.last_name" => "Demir"])->take();
  //   // print_r($this->users);
  //   // echo "<br/>### join kullanıldı!<br/>";
  //   $comment = Comment::find(4);
  //   echo $comment->user->first_name;

    // $users = User::all();
    // foreach ($users as $user) {
    //   echo $user->first_name . " - " . $user->department->name . "<br/>";
    // }
    // echo "<br/>";   echo "<br/>";

		$department = Department::load()->joins(["User", "Address"])->where(["User.id" => "1"])->select("User.first_name, Department.name, Address.phone")->limit(1)->take();
		print_r($department);
		echo "<br/>";   echo "<br/>";


		$departments = Department::all();
		foreach ($departments as $department) {
			echo $department->name . "<br/>";
			$users = User::load()->where(["department_id" => $department->id]);
			foreach ($users as $user) {
				echo $user->first_name . "<br/>";
			}
		}

		echo "<br/><b>### joins kullanıldı!</b><br/>";
  //   print_r($this->users);
  //   echo "<br/>### join+select kullanıldı!<br/>";

    // $this->users = User::load()->group("first_name, last_name")->select("User.first_name")->take();
    // echo ">>>><br/><br/><br/>";
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
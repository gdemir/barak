<?php
class AdminController extends ApplicationController {

  protected $before_actions = [
                                  ["name" => "require_login", "except" => ["login"]]
                              ];



  public function login() {
  	if (isset($_SESSION['admin']))
			return $this->redirect_to("/admin/home");

    if (isset($_POST["username"]) and isset($_POST["password"])) {
			$user = User::where([
				"username" => $_POST["username"],
				"password" => $_POST["password"]
				]);
			if ($user) {
				echo "tebrikler";
				$_SESSION["admin"] = true;
				return $this->render("/admin/home");
			} else
				echo "şifre veya parola hatalı";
		}
		echo "otomatik render, login paneli gelmeli";
  }

  // public function home() {
  // 	session_destroy();
  // }

  public function require_login() {
    echo "Her işlem öncesi login oluyoruz<br/>";
    if (!isset($_SESSION['admin']))
      return $this->redirect_to("/admin/login");
  }
}
?>
<?php
class AdminController extends ApplicationController {

  protected $before_actions = [
  ["require_login", "except" => ["login", "logout"]]
  ];

  public function login() {

    if (isset($_SESSION["admin"]))
      return $this->redirect_to("/admin/home");

    if (isset($_POST["username"]) and isset($_POST["password"])) {

      if ($user = User::unique(["username" => $_POST["username"], "password" => $_POST["password"]])) {

        $GLOBALS["success"] = "Admin sayfasına hoş geldiniz";
        $_SESSION['full_name'] = "$user->first_name $user->last_name";
        $_SESSION["admin"] = true;
        return $this->render("/admin/home");

      } else {
echo "evet";
        $GLOBALS['danger'] = "şifre veya parola hatalı";
        echo $GLOBALS['danger'];

      }
    }
    return $this->render(["layout" => "default"]);
  }

  public function home() { } // OPTIONAL

  public function logout() {
    if (isset($_SESSION["admin"])) session_destroy();
    return $this->redirect_to("/admin/login");
  }

  public function require_login() {
    if (!isset($_SESSION['admin']))
      return $this->redirect_to("/admin/login");
  }
}
?>
<?php

class UserController extends AdminController {

  public function index() {
    $this->users = User::all();
  }

  public function new() {}

  public function create() {

    // rastgele bir parolar belirle
    $alphabet = "abcdefghijklmnopqrstuwxyzABC0123456789";
    for ($i = 0; $i < 8; $i++) {
      $random_password[$i] = $alphabet[rand(0, strlen($alphabet) - 1)];
    }
    $random_password = implode("", $random_password);
    $_POST["password"] = md5($random_password);

    $user = User::new($_POST);
    $user->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa yeni resmi ekle

      $user->image = ImageHelper::file_upload($image, "/upload/user", $user->id);
      $user->save();
    }

    $this->redirect_to("/admin/user/show/" . $user->id);
  }

  public function show() {
    if (!$this->user = User::find($this->id))
      return $this->redirect_to("/admin/user");
  }

  public function edit() {
    if (!$this->user = User::find($this->id))
      return $this->redirect_to("/admin/user");
  }

  public function update() {
    $user = User::find($_POST["id"]);
    foreach ($_POST as $key => $value) $user->$key = $value;
    $user->save();

    $image = $_FILES['image'];
    if ($image["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $user->image = ImageHelper::file_update($user->image, $image, "/upload/user", $user->id);
      $user->save();
    }

    $this->redirect_to("/admin/user/show/" . $user->id);
  }

  public function destroy() {
    $user = User::find($_POST["id"]);

    ImageHelper::file_remove($user->image);

    $user->destroy();
    return $this->redirect_to("/admin/user");
  }
}

?>
<?php

class CategoryController extends AdminController {


  public function index() {
    $this->categories = Category::all();
  }

//  public function new() {}

  public function create() {

    $category = Category::create($_POST);

    $file = $_FILES['image'];
    if ($file["name"] != "") {

      // varsa bir önceki resmi sil
      ImageHelper::file_remove($category->image);
      // yeni resmi ekle
      $path_parts = pathinfo($file["name"]);
      $image_path = ImageHelper::file_upload($file["tmp_name"], "/upload/category", $category->id . "." . strtolower($path_parts["extension"]));

      $category->image = $image_path;
      $category->save();
    }

    $this->redirect_to("/admin/category/show/" . $category->id);
  }

  public function show() {

    if (!$this->category = Category::find($this->id))
      return $this->redirect_to("/admin/category/index");

  }

  public function edit() {

    if (!$this->category = Category::find($this->id))
      return $this->redirect_to("/admin/category/index");
  }

  public function update() {
    $id = $_POST["id"];
    $category = Category::find($id);

    foreach ($_POST as $key => $value) $category->$key = $value;

    $category->save();

    $file = $_FILES['image'];
    if ($file["name"] != "") {

      // varsa bir önceki resmi sil
      ImageHelper::file_remove($category->image);
      // yeni resmi ekle
      $path_parts = pathinfo($file["name"]);
      $image_path = ImageHelper::file_upload($file["tmp_name"], "/upload/category", $category->id . "." . strtolower($path_parts["extension"]));

      $category->image = $image_path;
      $category->save();
    }

    $this->redirect_to("/admin/category/show/" . $id);
  }

  public function destroy() {
    $id = $_POST["id"];
    $category = Category::find($id);

    ImageHelper::file_remove($category->image);
    $category->destroy();

    return $this->redirect_to("/admin/category/index");
  }

}

?>
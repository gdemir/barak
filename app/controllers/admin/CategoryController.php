<?php

class CategoryController extends AdminController {


  public function index() {
    $this->categories = Category::all();
  }

  public function new() {}

  public function create() {
    $category = Category::new($_POST);
    $category->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa yeni resmi ekle

      $category->image = ImageHelper::file_upload($image, "/upload/category", $category->id);
      $category->save();
    }

    $this->redirect_to("/admin/category/show/" . $category->id);
  }

  public function show() {
    if (!$this->category = Category::find($this->id))
      return $this->redirect_to("/admin/category");
  }

  public function edit() {
    if (!$this->category = Category::find($this->id))
      return $this->redirect_to("/admin/category");
  }

  public function update() {
    $category = Category::find($_POST["id"]);
    foreach ($_POST as $key => $value) $category->$key = $value;
    $category->save();

    $image = $_FILES['image'];
    if ($image["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $category->image = ImageHelper::file_update($category->image, $image, "/upload/category", $category->id);
      $category->save();
    }

    $this->redirect_to("/admin/category/show/" . $category->id);
  }

  public function destroy() {
    $category = Category::find($_POST["id"]);

    ImageHelper::file_remove($category->image);

    $category->destroy();
    return $this->redirect_to("/admin/category");
  }
}

?>
<?php

class CategoriesController extends AdminController {


  public function index() {
    $this->categories = Category::all();
  }

  public function create() {}

  public function save() {
    $category = Category::draft($_POST);
    $category->created_at = date("Y-m-d H:i:s");
    $category->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa yeni resmi ekle

      $category->image = ImageHelper::file_upload($image, "/upload/categories", $category->id);
      $category->save();
    }

    $_SESSION["success"] = "Yeni kategori eklendi";
    $this->redirect_to("/admin/categories/show/" . $category->id);
  }

  public function show() {
    if (!$this->category = Category::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir kategori bulunmamaktadır";
      return $this->redirect_to("/admin/categories");
    }
  }

  public function edit() {
    if (!$this->category = Category::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir kategori bulunmamaktadır";
      return $this->redirect_to("/admin/categories");
    }
  }

  public function update() {
    $category = Category::find($_POST["id"]);
    foreach ($_POST as $key => $value) $category->$key = $value;
    $category->updated_at = date("Y-m-d H:i:s");
    $category->save();

    $image = $_FILES['image'];
    if ($image["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $category->image = ImageHelper::file_update($category->image, $image, "/upload/categories", $category->id);
      $category->save();
    }

    $_SESSION["info"] = "Kategori güncellendi";
    $this->redirect_to("/admin/categories/show/" . $category->id);
  }

  public function destroy() {
    $category = Category::find($_POST["id"]);
    ImageHelper::file_remove($category->image);
    $category->destroy();

    $_SESSION["info"] = "Kategori silindi";
    return $this->redirect_to("/admin/categories");
  }
}

?>
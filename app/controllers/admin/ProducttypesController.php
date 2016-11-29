<?php

class ProducttypesController extends AdminController {

  public function index() {
    $this->producttypes = Producttype::all();
  }

  public function create() {
    $this->categories = Category::all();
  }

  public function save() {
    $producttype = Producttype::draft($_POST);
    $producttype->created_at = date("Y-m-d H:i:s");
    $producttype->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa yeni resmi ekle
      $producttype->image = ImageHelper::file_upload($image, "/upload/producttypes", $producttype->id);
      $producttype->save();
    }

    $_SESSION["success"] = "Yeni ürün tipi eklendi";
    $this->redirect_to("/admin/producttypes/show/" . $producttype->id);
  }

  public function show() {
    if (!$this->producttype = Producttype::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün tipi bulunmamaktadır";
      return $this->redirect_to("/admin/producttypes");
    }
  }

  public function edit() {
    $this->categories = Category::all();
    if (!$this->producttype = Producttype::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün tipi bulunmamaktadır";
      return $this->redirect_to("/admin/producttypes");
    }
  }

  public function update() {
    $producttype = Producttype::find($_POST["id"]);
    foreach ($_POST as $key => $value) $producttype->$key = $value;
    $producttype->updated_at = date("Y-m-d H:i:s");
    $producttype->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $producttype->image = ImageHelper::file_update($producttype->image, $image, "/upload/producttypes", $producttype->id);
      $producttype->save();
    }

    $_SESSION["info"] = "Ürün tipi güncellendi";
    $this->redirect_to("/admin/producttypes/show/" . $producttype->id);
  }

  public function destroy() {
    $producttype = Producttype::find($_POST["id"]);

    ImageHelper::file_remove($producttype->image);

    $producttype->destroy();
    $_SESSION["info"] = "Ürün tipi silindi";
    return $this->redirect_to("/admin/producttypes");
  }

}
?>
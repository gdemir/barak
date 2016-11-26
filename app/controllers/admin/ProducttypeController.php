<?php

class ProducttypeController extends AdminController {

  public function index() {
    $this->producttypes = Producttype::all();
  }

  public function new() {
    $this->categories = Category::all();
  }

  public function create() {
    $producttype = Producttype::new($_POST);
    $producttype->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa yeni resmi ekle
      $producttype->image = ImageHelper::file_upload($image, "/upload/producttype", $producttype->id);
      $producttype->save();
    }

    $_SESSION["success"] = "Yeni ürün tipi eklendi";
    $this->redirect_to("/admin/producttype/show/" . $producttype->id);
  }

  public function show() {
    if (!$this->producttype = Producttype::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün tipi bulunmamaktadır";
      return $this->redirect_to("/admin/producttype");
    }
  }

  public function edit() {
    $this->categories = Category::all();
    if (!$this->producttype = Producttype::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün tipi bulunmamaktadır";
      return $this->redirect_to("/admin/producttype");
    }
  }

  public function update() {
    $producttype = Producttype::find($_POST["id"]);
    foreach ($_POST as $key => $value) $producttype->$key = $value;
    $producttype->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $producttype->image = ImageHelper::file_update($producttype->image, $image, "/upload/producttype", $producttype->id);
      $producttype->save();
    }

    $_SESSION["info"] = "Ürün tipi güncellendi";
    $this->redirect_to("/admin/producttype/show/" . $producttype->id);
  }

  public function destroy() {
    $producttype = Producttype::find($_POST["id"]);

    ImageHelper::file_remove($producttype->image);

    $producttype->destroy();
    $_SESSION["info"] = "Ürün tipi silindi";
    return $this->redirect_to("/admin/producttype");
  }

}
?>
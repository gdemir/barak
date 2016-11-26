<?php

class ProductController extends AdminController {

  public function index() {
    $this->products = Product::all();
  }

  public function new() {
    $this->categories = Category::all();
  }

  public function create() {
    $product = Product::new($_POST);
    $product->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa yeni resmi ekle
      $product->image = ImageHelper::file_upload($image, "/upload/product/image", $product->id);
      $product->save();
    }

    $file = $_FILES["file"];
    if ($file["name"] != "") {// varsa yeni resmi ekle
      $product->file = ImageHelper::file_upload($file, "/upload/product/file", $product->id);
      $product->save();
    }

    $_SESSION["success"] = "Yeni ürün eklendi";
    $this->redirect_to("/admin/product/show/" . $product->id);
  }

  public function show() {
    if (!$this->product = Product::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün bulunmamaktadır";
      return $this->redirect_to("/admin/product");
    }
  }

  public function edit() {
  	$this->categories = Category::all();
    if (!$this->product = Product::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün bulunmamaktadır";
      return $this->redirect_to("/admin/product");
    }
  }

  public function update() {
    $product = Product::find($_POST["id"]);
    foreach ($_POST as $key => $value) $product->$key = $value;
    $product->save();

    $image = $_FILES["image"];
    if ($image["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $product->image = ImageHelper::file_update($product->image, $image, "/upload/product/image", $product->id);
      $product->save();
    }

    $file = $_FILES["file"];
    if ($file["name"] != "") {// varsa bir önceki resmi sil ve yeni resmi ekle
      $product->file = ImageHelper::file_update($product->file, $file, "/upload/product/file", $product->id);
      $product->save();
    }

    $_SESSION["info"] = "Ürün güncellendi";
    $this->redirect_to("/admin/product/show/" . $product->id);
  }

  public function destroy() {
    $product = Product::find($_POST["id"]);

    ImageHelper::file_remove($product->image);
    ImageHelper::file_remove($product->file);

    $product->destroy();
    $_SESSION["info"] = "Ürün silindi";
    return $this->redirect_to("/admin/product");
  }

}
?>
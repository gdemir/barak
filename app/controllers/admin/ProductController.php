<?php

class ProductController extends AdminController {

  public function index() {
    $this->products = Product::all();
  }

  public function new() {}

  public function create() {

    $product = Product::create($_POST);
    $this->redirect_to("/admin/product/show/" . $product->id);
  }

  public function show() {

    if (!$this->product = Product::find($this->id))
      return $this->redirect_to("/admin/product/index");
  }

  public function edit() {

    if (!$this->product = Product::find($this->id))
      return $this->redirect_to("/admin/product/index");
  }

  public function update() {
    $id = $_POST["id"];
    Product::update($id, $_POST);
    $this->redirect_to("/admin/product/show/" . $id);
  }

  public function destroy() {
    $id = $_POST["id"];
    Product::delete($id);
    return $this->redirect_to("/admin/product/index");
  }

}
?>
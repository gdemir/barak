<?php

class ProducttypeController extends AdminController {

  public function index() {
    $this->producttypes = Producttype::all();
  }

  public function new() {
    $this->categories = Category::all();
  }

  public function create() {

    $producttype = Producttype::create($_POST);
    $this->redirect_to("/admin/producttype/show/" . $producttype->id);

  }

  public function show() {

    if (!$this->producttype = Producttype::find($this->id))
      return $this->redirect_to("/admin/producttype/index");
  }

  public function edit() {
    $this->categories = Category::all();

    if (!$this->producttype = Producttype::find($this->id))
      return $this->redirect_to("/admin/producttype/index");
  }

  public function update() {
    $id = $_POST["id"];
    Producttype::update($id, $_POST);
    $this->redirect_to("/admin/producttype/show/" . $id);
  }

  public function destroy() {
    $id = $_POST["id"];
    Producttype::delete($id);
    return $this->redirect_to("/admin/producttype/index");
  }

}
?>
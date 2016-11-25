<?php

class ProductfeatureController extends AdminController {

  public function index() {
    $this->productfeatures = Productfeature::all();
  }

  public function new() {
    $products = Product::all();

    $_products = [];
    foreach ($products as $product)
      array_push($_products, array(
        "id" => $product->id,
        "value" => $product->name,
        "label" =>
        "<img src='" . $product->image . "' width='50'; height='50' class='img-thumbnail'/>"
        . $product->name
        ));
    $this->products = $_products;
  }

  public function create() {

    $productfeature = Productfeature::new($_POST);
    $productfeature->save();
    $this->redirect_to("/admin/productfeature/show/" . $productfeature->id);
  }

  public function show() {

    if (!$this->productfeature = Productfeature::find($this->id))
      return $this->redirect_to("/admin/productfeature/index");

  }

  public function edit() {

    if (!$this->productfeature = Productfeature::find($this->id))
      return $this->redirect_to("/admin/productfeature/index");
  }

  public function update() {
    $id = $_POST["id"];
    Productfeature::update($id, $_POST);
    $this->redirect_to("/admin/productfeature/show/" . $id);
  }

  public function destroy() {
    $id = $_POST["id"];
    Productfeature::delete($id);
    return $this->redirect_to("/admin/productfeature/index");
  }

}

?>
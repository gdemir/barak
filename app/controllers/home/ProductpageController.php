<?php

class ProductpageController extends HomeController {

  public function index() {

    $this->products = Product::all();

  }

  public function search() {
    $this->categories = Category::all();
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

  public function find() {

    if (!Product::find($_POST["product_id"]))
      return $this->redirect_to("/home/index");
    return $this->redirect_to("/home/productpage/show/" . $_POST["product_id"]);
  }

  // public function show() {}

}

?>
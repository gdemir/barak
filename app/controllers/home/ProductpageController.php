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

    if (!$product = Product::find($_POST["product_id"]))
      return $this->redirect_to("/home/index");
    return $this->redirect_to("/home/products/show/" . $product->id);
  }

  public function show() {
    if (!$this->product = Product::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün bulunmamaktadır";
      return $this->redirect_to("/home/products");
    }
  }

}

?>
<?php

class AjaxController extends ApplicationController {

  public function producttype() {
    $producttypes = Producttype::load()->where(["category_id" => $_POST['category_id']])->take();
    $text = "";
    foreach ($producttypes as $producttype)
      $text .= "<option value='" . $producttype->id . "'>" . $producttype->name . "</option>";
    $this->render(["text" => $text]);
  }

  // public function product() {
  //   $products = Product::load()->where(["producttype_id" => $_POST['producttype_id']])->take();
  //   $text = "";
  //   foreach ($products as $product)
  //     $text .= "<option value='" . $product->id . "'>" . $product->name . "</option>";
  //   $this->render(["text" => $text]);
  // }

}

?>
<?php

class ProducttypepageController extends HomeController {


  public function index() {
  	$this->producttypes = Producttype::all();
  }

  public function find() {
    if (!$producttype = Producttype::find($_POST["producttype_id"]))
      return $this->redirect_to("/home/index");
    return $this->redirect_to("/home/producttypepage/show/" . $producttype->id);
  }

  public function show() {
    if (!$this->producttype = Producttype::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün tipi bulunmamaktadır";
      return $this->redirect_to("/home/producttypepage");
    }
    $this->products = $this->producttype->all_of_product;
  }

}

?>
<?php

class ProducttypepageController extends HomeController {


  public function index() {

  }

  public function show() {
    if (!$this->producttype = Producttype::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir ürün tipi bulunmamaktadır";
      return $this->redirect_to("/home/producttypepage");
    }
  }

}

?>
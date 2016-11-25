<?php

class ProducttypepageController extends HomeController {


  public function index() {

  }

  public function find() {

    if (!Producttype::find($_POST["producttype_id"]))
      return $this->redirect_to("/home/index");
    return $this->redirect_to("/home/producttypepage/show/" . $_POST["producttype_id"]);
  }

  public function show() {}

}

?>
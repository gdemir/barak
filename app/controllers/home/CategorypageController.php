<?php

class CategorypageController extends HomeController {


  public function index() {
    $this->categories = Category::all();
  }

  public function show() {
    if (!$this->category = Category::find($this->id)) {
      $_SESSION["danger"] = "Böyle bir kategori bulunmamaktadır";
      return $this->redirect_to("/home/categories");
    }
    $this->producttypes = $this->category->all_of_producttype;
  }

}

?>
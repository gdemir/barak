<?php

class CategorypageController extends HomeController {


  public function index() {
    $this->categories = Category::all();
  }

  public function show() {
    $this->category = Category::find($this->id);
    $this->producttypes = Producttype::load()->where(["category_id" => $this->category->id])->take();
  }

}

?>
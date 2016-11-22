<?php

class CategorypageController extends HomeController {


  public function index() {
    $this->categories = Category::all();
  }

  public function show() {

    if (!$this->category = Category::find($this->id))
      return $this->redirect_to("/home/index");
  }

}

?>
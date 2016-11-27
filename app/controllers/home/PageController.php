<?php

class PageController extends HomeController {


  public function index() {
  	print_r($this);
  }
  public function categories() {
  	$this->categories = Category::all();
  }

}

?>
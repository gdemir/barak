<?php
class HomeController extends ApplicationController {
	private $before_filter = "require_login";
	public function require_login()
	{
	  echo "login olmadan asla";
	}
	public function index()
	{
	  echo "Merhaba home#index";
	}
}
?>

<?php
class UsersController extends ApplicationController {
	$before_filter = "require_login";
	public function require_login()
	{
	  echo "login olmadan asla";
	}
	public function login()
	{
	  echo "login olduk";
	}
}
?>

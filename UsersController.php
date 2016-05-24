<?php
class UsersController extends ApplicationController {
			before_filter = "require_login";
			public require_login()
			{
			  echo "login olmadan asla";
			}
			public login()
			{
			  echo "login olduk";
			}
		}
?>

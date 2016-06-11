<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>
  <link href="" rel="alternate" title="" type="application/atom+xml" />
  <link rel="shortcut icon" href="../assets/img/default.ico">
  <link rel="stylesheet" href="../assets/css/syntax.css" type="text/css" />
  <link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <script src="../assets/js/respond.min.js"></script>
   <![endif]-->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
</head>
<body>
  <?php
echo "Showwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww";

	foreach ($users as $user) {
		echo $user->tc . "<br/>";
	}
	$render = "/home/index";
	$render = "index";
	$render = explode("/", trim($render, "/"));
	print_r($render);
	if isset($render[1])
		echo "evet";
	else
		echo "hayir";
?>

</body>
</html>
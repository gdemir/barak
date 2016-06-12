<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>
  <link href="" rel="alternate" title="" type="application/atom+xml" />
  <link rel="shortcut icon" href="/app/assets/img/default.ico">
  <link rel="stylesheet" href="/app/assets/css/syntax.css" type="text/css" />
  <link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <script src="../assets/js/respond.min.js"></script>
   <![endif]-->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="http://code.jquery.com/jquery.js"></script>
  <script src="/app/assets/js/bootstrap.min.js"></script>
</head>
<body style='background-color:red;'>

  <div class="container" style="width:500px; min-height:200px; margin-top: 8%; ">

    <div class="panel panel-default" style="box-shadow: 0 3px 12px rgba(0, 0, 0, 0.3);">
      <div class="panel-heading">
        <h4 class="panel-title">Kullanıcı Girişi</h4>
      </div>
      <div class="panel-body">
        {yield}
      </div>
      <div class="panel-footer">BARAK Copyright &copy; <?php echo date("Y"); ?></div>
    </div>

  </div>

</body>
</html>
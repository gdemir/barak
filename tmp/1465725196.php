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
<body style='background-color:red;'>
  <div class="row">
  <div class="col-xs-3">
    <img src="<?php echo Helper::server_url(); ?>/assets/img/default.png" class="img-thumbnail" />
  </div>
  <div class="col-xs-9">
    <form class="login-form" action="/admin/login" accept-charset="UTF-8" method="post">
      <input type="text" placeholder="Kullanıcı Adı" class="form-control" size="50" name="username" id="username" />
      <input type="password" placeholder="Parola" class="form-control" size="50" name="password" id="password" />
      <button type="submit" class="btn btn-primary" style="width:100%">SİSTEME GİRİŞ</button>
    </form>
  </div>
</div>
</body>
</html>
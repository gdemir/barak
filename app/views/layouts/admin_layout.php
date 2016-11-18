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
<body>

<?php render("admin_navbar"); ?>

<div class="well well-sm" style="padding:0em">
  <ul class="nav nav-pills well well-sm" style="margin-bottom:0px">
    <li role="presentation">
      <a href="/admin/home" class="fa fa-home">
        Anasayfa
      </a>
    </li>
    <li role="presentation">
      <a class="fa fa-bars" id="side-menu-close"></a>
      <a class="fa fa-bars" id="side-menu-open"></a>
    </li>
  </ul>
</div>
<div class="row">
  <div class="well well-sm col-xs-2 col-md-2" id="side-menu">
    <ul class="nav nav-pills nav-stacked" role="ablist" id="accordion1">
      <hr>

      <span class="label label-info">Evrak</span>
      <hr>

      <div id="data-menu">

        <div class="panel list-group">
          <a class="list-group-item" data-toggle="collapse" data-target="#datas" data-parent="#data-menu">
            <span class="hidden-xs hidden-sm hidden-md">
              <i class="fa fa-download"></i> Evraklar
              <span class="label label-warning">BETA</span>
            </span>
            <i class="fa fa-download fa-2x visible-xs visible-sm visible-md"></i>
          </a>

          <ul class="nav nav-pills nav-stacked collapse" role="ablist" id="datas">
            <li role="presentation">
              <a href="/admin/index.php?yield=datas/index">
                <span class="hidden-xs hidden-sm hidden-md"><i class="glyphicon glyphicon-chevron-right"></i> Listele</span>
                <i class="fa fa-list fa-1x visible-xs visible-sm visible-md" title="Listele"></i>
              </a>
            </li>
            <li role="presentation">
              <a href="/admin/index.php?yield=datas/new">
                <span class="hidden-xs hidden-sm hidden-md"><i class="glyphicon glyphicon-chevron-right"></i> Ekle</span>
                <i class="fa fa-plus fa-1x visible-xs visible-sm visible-md" title="Ekle"></i>
              </a>
            </li>
          </ul>
        </div>

      </div>

      <hr>

    </ul>
  </div>
  <div class="col-xs-10 col-md-10" id="main-menu">
    <div class="well well-sm">
      <ol class="breadcrumb text-right">
        <li>
          <a href="/admin" class="btn btn-default btn-sm">
            <i class="fa fa-home "> Home</i>
          </a>
        </li>
      </ol>

      <!-- bildirimleri göster ve temizle -->

      <?php render("notice", "layouts"); ?>


     {yield}

    </div>

  </div>
  <script>
  $(document).ready(function(){

    $('#side-menu-close').hide();
    $('#side-menu-open').click(function() {
      $('#side-menu-close').show();
      $('#side-menu-open').hide();

      $('#side-menu').fadeOut("slide");
      $("#main-menu").removeClass();
      $("#main-menu").addClass("col-xs-12 col-md-12");
    });
    $('#side-menu-close').click(function() {
      $('#side-menu-open').show();
      $('#side-menu-close').hide();

      $('#side-menu').fadeIn("slide");
      $("#main-menu").addClass("col-xs-10 col-md-10");
    });

  });
  </script>
</body>
</html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?></title>
  <link href="" rel="alternate" title="" type="application/atom+xml" />
  <link rel="shortcut icon" href="/app/assets/img/default.ico">
  <link rel="stylesheet" href="/app/assets/css/syntax.css" type="text/css" />
  <link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="/app/assets/js/html5shiv.js"></script>
    <script src="/app/assets/js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="/app/assets/js/bootstrap.min.js"></script>

    <!-- Google Analytics start -->
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-73568943-1', 'auto');
    ga('send', 'pageview');
    </script>
    <!-- Google Analytics end -->

    <!-- datepicker start -->
    <!-- source: https://github.com/eternicode/bootstrap-datepicker -->
    <link rel="stylesheet" href="/app/assets/css/bootstrap-datepicker.min.css" type="text/css" />
    <script src="/app/assets/js/bootstrap-datepicker.min.js"></script>
    <script src="/app/assets/js/bootstrap-datepicker.tr.js"></script>
    <!-- datepicker end -->

    <!-- bootstrap-image-gallery start -->
    <!-- source: https://github.com/blueimp/Gallery -->
    <link rel="stylesheet" href="/app/assets/css/blueimp-gallery.min.css" type="text/css" />
    <script src="/app/assets/js/blueimp-gallery.min.js"></script>
    <!-- bootstrap-image-gallery end -->

    <!-- auto search start -->
    <script src="/app/assets/js/typeahead.bundle.js"></script>
    <!-- auto search end -->

    <!-- bxSlider start -->
    <!-- source: http://bxslider.com/ -->
    <script src="/app/assets/js/jquery.bxslider.min.js"></script>
    <link href="/app/assets/css/jquery.bxslider.css" rel="stylesheet" />
    <!-- bxSlider end -->
  </head>
  <body>

    <div class="container">
      <?php render("home_navbar"); ?>
      <div class="well well-sm">

        <div class="row">
          <div class="col-xs-8">

            <ul class="nav nav-pills">
              <li role="presentation">
                <a href="/home/index"><?= t("homepage"); ?></a>
              </li>
              <li role="presentation" class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?= t("corporate.corporate"); ?>
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="/home/about"><?= t("corporate.about"); ?></a></li>
                  <li><a href="/home/service_policy"><?= t("corporate.service_policy"); ?></a></li>
                  <li><a href="/home/our_focus"><?= t("corporate.our_focus"); ?></a></li>
                  <li><a href="/home/human_resources"><?= t("corporate.human_resources"); ?></a></li>
                </ul>
              </li>
              <li role="presentation" class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?= t("services.services"); ?>
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <?php foreach($categories as $category) { ?>
                  <li><a href="/home/categorypage/show/<?= $category->id; ?>"><?= $category->name; ?></a></li>
                  <?php } ?>
                </ul>
              </li>
              <li role="presentation"><a href="/home/contact"><?= t("contact"); ?></a></li>
            </ul>
          </div>
          <div class="col-xs-4 hidden-xs hidden-sm">
            <!--<img src="/app/assets/img/signature_of_ataturk.svg.png" width="120" class="img-responsive pull-right"/>-->
            <ul class="nav nav-pills pull-right">
              <li role="presentation"><a href="/lang/tr"><img src="/app/assets/img/tr.png" class="img-border"/></a></li>
              <li role="presentation"><a href="/lang/en"><img src="/app/assets/img/en.png" class="img-border"/></a></li>
            </ul>

          </div>
        </div>

      </div>

      <div class="well">

        {yield}

      </div>
      <div class="well well-sm">
        <div class="row">
          <div class="col-md-9 col-sm-12 col-xs-12">
            <a href="http://teias.gov.tr" target="_blank"><img src="/app/assets/img/default-side.svg.png" width="120"/></a>
            tarafından tüm içerik hakları saklıdır.
          </div>

          <div class="col-md-3 hidden-sm hidden-xs">
            <a href="http://www.turkeydiscoverthepotential.com/" target="_blank">
              <img src="/app/assets/img/signature_of_turkey.svg.png" width="150" class="pull-right"/>
            </a>
          </div>
        </div>
      </div>

      <?php render('signin_modal'); ?>
      <?php render('home_footer'); ?>
      <?php render('nav-up-down', 'layouts'); ?>
      <!-- dropdown hover start -->
      <script src="/app/assets/js/bootstrap-hover-dropdown.min.js"></script>
      <script type="text/javascript">
      $('.dropdown-toggle').dropdownHover();
      </script>
      <!-- dropdown hover end -->
    </div>
  </body>
  </html>
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
    <script src="/app/assets/js/html5shiv.js"></script>
    <script src="/app/assets/js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
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

    <!-- Animatecss start -->
    <!-- source: https://daneden.github.io/animate.css/ -->
    <link rel="stylesheet" type="text/css" href="/app/assets/css/animate.min.css" />

    <!-- Animatecss end -->

    <!-- bxSlider start -->
    <!-- source: http://bxslider.com/ -->
    <script src="/app/assets/js/jquery.bxslider.min.js"></script>
    <link href="/app/assets/css/jquery.bxslider.css" rel="stylesheet" />
    <!-- bxSlider end -->
  </head>
  <body>

  	<?php render("home_navbar"); ?>

  	<div class="well well-sm">

  		<div class="row">

  			<div class="col-xs-8">
  				<ul class="nav nav-pills">
  					<li role="presentation"><a href="/home/index"><?= t("home"); ?></a></li>
  					<li role="presentation" class="dropdown">
  						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?= t("corporate"); ?>
  							<b class="caret"></b>
  						</a>
  						<ul class="dropdown-menu">
  							<li><a href="/home/about"><?= t("about"); ?></a></li>
  							<li><a href="/home/service_policy"><?= t("service_policy"); ?></a></li>
  							<li><a href="/home/our_focus"><?= t("our_focus"); ?></a></li>
  							<li><a href="/home/human_resources"><?= t("human_resources"); ?></a></li>
  						</ul>
  					</li>

  					<li role="presentation" class="dropdown">
  						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?= t("productpage"); ?>
  							<b class="caret"></b>
  						</a>
  						<ul class="dropdown-menu">

  							<li class="dropdown-submenu">
  								<a tabindex="-1" href="/home/categorypage"><?= t("categorypage"); ?></a>
  								<ul class="dropdown-menu">
  									<?php foreach (Category::all() as $category) { ?>
  									<li class="dropdown-submenu">
  										<a href="/home/categorypage/show/<?= $category->id; ?>"><?= $category->name; ?></a>
  										<ul class="dropdown-menu">
  											<?php foreach (Producttype::load()->where(["category_id" => $category->id])->take() as $producttype) { ?>
  											<li><a href="/home/producttypepage/show/<?= $producttype->id; ?>"><?= $producttype->name; ?></a></li>
  											<?php } ?>
  										</ul>
  									</li>
  									<?php } ?>
  								</ul>
  							</li>

  							<li><a href="/home/productpage/search"><?= t("search"); ?></a></li>
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

  	<div class="well well-sm">

  		<?= BootstrapHelper::page_title_and_breadcrumb() ?>

  		{yield}

  	</div>

  	<div class="well well-lg" style="background-color:#465568; color:white">

  		<div class="row">
  			<div class="col-md-9 col-sm-12 col-xs-12">
  				Aksi belirtilmedikçe <a href="http://olt.com.tr" target="_blank">olt.com.tr</a> tarafından tüm içerik hakları saklıdır.
  			</div>
  			<div class="col-md-3 hidden-sm hidden-xs">
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


  </body>
  </html>
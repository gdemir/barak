<nav class="navbar navbar-default animated fadeInDown">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"href="/home/index" style="padding:0em;margin-left:0px;">
        <img alt="Brand" src="/app/assets/img/default-side3.svg.png" width="120" class="img-responsive"/>
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-left">
        <li>
          <!-- Button trigger modal -->
          <p style="margin-top:14px;font-size:16px;font-weight:bold;color:#434344;letter-spacing:1px;">/ Optik Line Telekomünikasyon</p>
          <!-- Button trigger modal end -->
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <!-- Button trigger modal -->
          <a data-toggle="modal" data-target="<?php if (!isset($_SESSION['admin'])) echo '#myModal1'; ?>" href="<?php if (isset($_SESSION['admin'])) echo '/admin/index'?>"><?= t("login.link"); ?></a>
          <!-- Button trigger modal end -->
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
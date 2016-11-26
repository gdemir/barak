<div class="panel panel-default" style="box-shadow: 0 3px 12px rgba(0, 0, 0, 0.3);">
  <div class="panel-heading">
    <h4 class="panel-title"><?= t("login.title"); ?></h4>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-xs-3">
        <img src="/app/assets/img/default.png" class="img-thumbnail" />
      </div>
      <div class="col-xs-9">
        <form class="login-form" action="/admin/login" accept-charset="UTF-8" method="post">
          <input type="text" placeholder="<?= t("login.username"); ?>" class="form-control" size="50" name="username" id="username" />
          <input type="password" placeholder="<?= t("login.password"); ?>" class="form-control" size="50" name="password" id="password" />
          <button type="submit" class="btn btn-primary" style="width:100%"><?= t("login.button"); ?></button>
        </form>
      </div>
    </div>
  </div>
  <div class="panel-footer">OLT Copyright &copy; <?php echo date("Y"); ?></div>
</div>
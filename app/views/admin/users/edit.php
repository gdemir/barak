<h4 class="page-title">Kullanıcı Düzenle</h4>

<div class='modal fade' id='image' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-body'>
        <img src="<?= $user->image; ?>" width='600' height='400' class='img-thumbnail' />
      </div>
    </div>
  </div>
</div>

<form class="form-horizontal" action="/admin/users/update" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
  <input type="hidden" value="<?= $user->id; ?>" name="id" id="id" />
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <img src="<?= $user->image; ?>" class='img-rounded' data-toggle='modal'data-target='#image' style="min-height:100px; height:100px;" />
        <div class="caption">
          <input type="file" id="image" name="image" class="form-control" />
        </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="first_name">Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->first_name; ?>" class="form-control" name="first_name" id="first_name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="last_name">Soyad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->last_name; ?>" class="form-control" name="last_name" id="last_name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="username">Kullancı Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->username; ?>" class="form-control" name="username" id="username" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="password">Yeni Şifre</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Yeni parola giriniz" class="form-control" name="password" id="password" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="phone">Telefon</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->phone; ?>" class="form-control" name="phone" id="phone" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="email">Email</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->email; ?>" class="form-control" name="email" id="email" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="cv">Özgeçmiş</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="cv" id="cv"><?= $user->cv; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="boss">Patron</label>
    <div class="col-sm-11">
      <select class="form-control" id="boss" name="boss">
        <?php if ($user->boss) { ?>
        <option value='1' selected>Evet</option>
        <option value='0'>Hayır</option>";
        <?php } else { ?>
        <option value='1'>Evet</option>
        <option value='0' selected>Hayır</option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="admin">Admin</label>
    <div class="col-sm-11">
      <select class="form-control" id="admin" name="admin">
        <?php if ($user->admin) { ?>
        <option value='1' selected>Evet</option>
        <option value='0'>Hayır</option>";
        <?php } else { ?>
        <option value='1'>Evet</option>
        <option value='0' selected>Hayır</option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary">Güncelle</button>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
  $('#cv').summernote({
    height: 200,
    minHeight: null,
    maxHeight: null,
    focus: true,
    lang: 'tr-TR'
  });
});
</script>
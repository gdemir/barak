<h4 class="page-title">Personel Bilgileri</h4>

<div class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <img src="<?= $user->image; ?>" width="600" height="400" class="img-thumbnail" />
      </div>
    </div>
  </div>
</div>

<form class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <img src="<?= $user->image; ?>" class="img-rounded" data-toggle="modal" data-target="#image" style="min-height:100px; height:100px;" />
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="first_name">Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->first_name; ?>" class="form-control" name="first_name" id="first_name" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="last_name">Soyad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->last_name; ?>" class="form-control" name="last_name" id="last_name" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="username">Kullancı Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->username; ?>" class="form-control" name="username" id="username" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="password">Şifre</label>
    <div class="col-sm-11">
      <input type="text" value="********" class="form-control" name="password" id="password" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="phone">Telefon</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $user->phone; ?>" class="form-control" name="phone" id="phone" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="cv">Özgeçmiş</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="cv" id="cv" disabled><?= $user->cv; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="boss">Patron</label>
    <div class="col-sm-11">
      <input type="text" value="<?= ($user->boss) ? 'Evet' : 'Hayır'; ?>" class="form-control" name="boss" id="boss" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="admin">Admin</label>
    <div class="col-sm-11">
      <input type="text" value="<?= ($user->admin) ? 'Evet' : 'Hayır'; ?>" class="form-control" name="admin" id="admin" disabled />
    </div>
  </div>

  <form class="form-horizontal" action="/admin/users/destroy" method="post">
    <input type="hidden" value="<?= $user->id; ?>" id="id" name="id" />
    <div class="form-group">
      <div class="col-sm-offset-1 col-sm-11">
        <a class="btn btn-primary" href="/admin/users/edit/<?= $user->id; ?>">Bilgileri Düzenle</a>
        <input type="submit" class="btn btn-danger" value="sil" onClick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" />
      </div>
    </div>
  </form>

  <script type="text/javascript">
  $(document).ready(function() {
    $('#cv').prop('disabled', true);
    $('#cv').summernote({
      toolbar: [],
      height: 200,
      minHeight: null,
      maxHeight: null,
      focus: true,
      lang: 'tr-TR'
    });
  });
  </script>
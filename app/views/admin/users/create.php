<h4 class="page-title">Personel Ekle</h4>

<form class="form-horizontal" action="/admin/users/save" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <input type="file" id="image" name="image" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="first_name">Ad</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Ad" class="form-control" name="first_name" id="first_name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="last_name">Soyad</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Soyad" class="form-control" name="last_name" id="last_name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="username">Kullancı Ad</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Kullanıcı Adı" class="form-control" name="username" id="username" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="phone">Telefon</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Telefon" class="form-control" name="phone" id="phone" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="email">Email</label>
    <div class="col-sm-11">
      <input type="text" placeholder="E-mail" class="form-control" name="email" id="email" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="cv">Özgeçmiş</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="cv" id="cv"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="boss">Patron</label>
    <div class="col-sm-11">
      <select class="form-control" id="boss" name="boss">
        <option value="1" selected>Evet</option>
        <option value="0">Hayır</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="admin">Admin</label>
    <div class="col-sm-11">
      <select class="form-control" id="admin" name="admin">
        <option value="1" selected>Evet</option>
        <option value="0">Hayır</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary">Oluştur</button>
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
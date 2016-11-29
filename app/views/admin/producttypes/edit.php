<h4 class="page-title">Ürün Tipi Düzenle</h4>

<form class="form-horizontal" action="/admin/producttypes/update" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
  <input type="hidden" value="<?= $producttype->id; ?>" name="id" id="id" />
  <div class="form-group">
    <label class="col-sm-1 control-label" for="category_id">Kategori</label>
    <div class="col-sm-11">
      <select class="form-control" id="category_id" name="category_id">
        <?php foreach ($categories as $category) { ?>
        <option value="<?= $category->id; ?>"<?= ($producttype->category_id == $category->id) ? "selected" : ""; ?>>
          <?= $category->name; ?>
        </option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="name">Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $producttype->name; ?>" class="form-control" size="50" name="name" id="name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="content">İçerik</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="content" id="content"><?= $producttype->content; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <img src="<?= $producttype->image; ?>" width="100" height="100" />
        <div class="caption">
          <input type="file" id="image" name="image" class="form-control" />
        </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary"
      onClick="return confirm('Güncellemek istediğinizden emin misiniz?');">Güncelle</button>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
  $('#content').summernote({
    height: 200,
    minHeight: null,
    maxHeight: null,
    focus: true,
    lang: 'tr-TR'
  });
});
</script>
<h4 class="page-title">Ürün Düzenle</h4>

<form class="form-horizontal" action="/admin/products/update" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
  <input type="hidden" value="<?= $product->id; ?>" name="id" id="id" />
  <div class="form-group">
    <label class="col-sm-1 control-label" for="category_id">Kategori</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->producttype->category->name; ?>" class="form-control" name="category_name" id="category_name" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="producttype_name">Ürün Tipi</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->producttype->name; ?>" class="form-control" name="producttype_name" id="producttype_name" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="name">Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->name; ?>" class="form-control" name="name" id="name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="content">İçerik</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="content" id="content"><?= $product->content; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="price">Fiyat</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->price; ?>" class="form-control" name="price" id="price" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <img src="<?= $product->image; ?>" width="100" height="100" />
        <div class="caption">
          <input type="file" id="image" name="image" class="form-control" />
        </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Dosya</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <embed src="<?= $product->file; ?>" style="width:100%;height:200px"></embed>
        <div style="text-align:center;">
          <a class="fa fa-download fa-3x" download href="<?= $product->file; ?>"></a>
        </div>
        <div class="caption">
          <input type="file" id="file" name="file" class="form-control" />
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
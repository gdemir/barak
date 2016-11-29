<h4 class="page-title">Ürün Bilgileri</h4>

<div class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-1 control-label" for="category_name">Kategori</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->producttype->category->name; ?>" class="form-control" name="category_name" id="category_name" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="producttype_name">Ürün Tip</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->producttype->name; ?>" class="form-control" name="producttype_name" id="producttype_name" disabled />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="name">Ad</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->name; ?>" class="form-control" name="name" id="name" disabled/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="content">İçerik</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="content" id="content" disabled><?= $product->content; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="price">Fiyat</label>
    <div class="col-sm-11">
      <input type="text" value="<?= $product->price; ?>" class="form-control" name="price" id="price" disabled/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <img src="<?= $product->image; ?> " width="100" height="100" />
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="file">Dosya</label>
    <div class="col-sm-11">
      <div class="thumbnail">
        <embed src="<?= $product->file; ?>" style="width:100%;height:150px"></embed>
        <div class="caption" style="text-align:center;">
          <a class="fa fa-download fa-3x" download href="<?= $product->file; ?>"></a>
        </div>
      </div>
    </div>
  </div>
</div>

<form class="form-horizontal" action="/admin/products/destroy" method="post">
  <input type="hidden" value="<?= $product->id; ?>" id="id" name="id" />
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <a class="btn btn-primary" href="/admin/products/edit/<?= $product->id; ?>">Düzenle</a>
      <input type="submit" class="btn btn-danger" value="sil"
      onClick="return confirm('Bu kaydı silmek istediğinizden emin misiniz?');" />
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
  $('#content').prop('disabled', true);
  $('#content').summernote({
    toolbar: [],
    height: 200,
    minHeight: null,
    maxHeight: null,
    focus: true,
    lang: 'tr-TR'
  });
});
</script>
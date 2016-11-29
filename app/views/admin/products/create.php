<h4 class="page-title">Ürün Ekle</h4>

<form class="form-horizontal" action="/admin/products/save" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label class="col-sm-1 control-label" for="category_id">Kategori</label>
    <div class="col-sm-11">
      <select class="form-control" id="category_id" name="category_id">
        <option value="">Kategori Seçiniz</option>
        <?php foreach ($categories as $category) { ?>
        <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group" id="producttype_id_box">
    <label class="col-sm-1 control-label" for="producttype_id">Ürün Tipi</label>
    <div class="col-sm-11">
      <select class="form-control" id="producttype_id" name="producttype_id">
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="name">Ad</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Ad" class="form-control" name="name" id="name" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="content">İçerik</label>
    <div class="col-sm-11">
      <textarea class="form-control" rows="10" name="content" id="content"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="price">Fiyat</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Ad" class="form-control" name="price" id="price" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="image">Resim</label>
    <div class="col-sm-11">
      <input type="file" id="image" name="image" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="file">Dosya</label>
    <div class="col-sm-11">
      <input type="file" id="file" name="file" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary">Oluştur</button>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function(){
  $("#producttype_id_box").hide();
  $("#producttype_id").hide();

  $("#category_id").change(function() {
    $("#producttype_id_box").show();
    $("#producttype_id").show();
    var category_id = $("#category_id").val();
    $.ajax({
      type : "post",
      url : "/ajax/producttype",
      data : "category_id="+category_id,
      success : function(data) {
        $("#producttype_id").html(data);
        $('select#producttype_id').find('option:first').attr('selected', 'selected').trigger('change');
      }
    });
  });

});
</script>
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
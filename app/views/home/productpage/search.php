<h4 class="page-title"><?= t("home.product_search"); ?></h4>
<ol class="breadcrumb text-right">
  <li><a href="/">Anasayfa</a></li>
  <li><a href="/home/products"><?= t("home.products"); ?></a></li>
  <li class="active"><?= t("home.product_search"); ?></li>
</ol>

<h5 class="page-title-sub">Ürün Hızlı Ara</h5>

<form class="form-horizontal" action="/home/products/find" accept-charset="UTF-8" method="post">
  <div class="form-group">
    <label class="col-sm-1 control-label" for="autocomplete">Ürün</label>
    <div class="col-sm-11">
      <input id="autocomplete" class="form-control"/>
      <div id="post"></div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary">Ara</button>
    </div>
  </div>
</form>

<h5 class="page-title-sub">Ürün Listele</h5>

<form class="form-horizontal" action="/home/producttypes/find" accept-charset="UTF-8" method="post">
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
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary">Ara</button>
    </div>
  </div>
</form>

<link rel="stylesheet" type="text/css" href="/app/assets/css/jquery-ui.css" />
<script src="/app/assets/js/jquery-ui.js"></script>
<script src="/app/assets/js/jquery.ui.autocomplete.html.js"></script>
<style>
.ui-autocomplete { max-height: 300px; min-width:500px; margin-top:30px;overflow-y: scroll; overflow-x: hidden;}
</style>
<script>

$(function() {
  $( "#autocomplete" ).autocomplete({
    source: <?= json_encode($products); ?>,
    select: function( event, ui ) {
      $("#post").html(
        "<input type='hidden' name='product_id' value='" + ui.item.id + "'/>"
        );
    },
    html: true
  });
});
</script>
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
        $('select#producttype_id').find('option:first').attr('selected','selected').trigger('change');
      }
    });
  });

});
</script>
<h4 class="page-title">Ürün Özelliği Ekle</h4>

<form class="form-horizontal" action="/admin/productfeature/create" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label class="col-sm-1 control-label" for="autocomplete">Kullanıcı</label>
    <div class="col-sm-11">
      <input id="autocomplete" class="form-control" />
      <div id="post"></div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-1 control-label" for="name">Özellik</label>
    <div class="col-sm-11">
      <input type="text" placeholder="Ad" class="form-control" name="name" id="name" />
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-11">
      <button type="submit" class="btn btn-primary">Oluştur</button>
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

<?php $product = Product::find($id); ?>

<h4 class="page-title"><?= t("home.product"); ?></h4>
<ol class="breadcrumb text-right">
  <li><a href="/">Anasayfa</a></li>
  <li><a href="/home/categories"><?= t("home.categories"); ?></a></li>
  <li><a href="/home/categories/show/<?= $product->producttype->category->id; ?>"><?= $product->producttype->category->name ?></a></li>
  <li><a href="/home/producttypes/show/<?= $product->producttype->id; ?>"><?= $product->producttype->name; ?></a></li>
  <li class="active"><?= $product->name; ?></li>
</ol>

<h5 class="page-title"><?= $product->name; ?></h5>

<div class="row">

  <div class="col-md-4 col-sm-6">

    <div class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <img src="<?= $product->image; ?>" width="600" height="400" class="img-thumbnail" />
          </div>
        </div>
      </div>
    </div>
    <div class="img-thumbnail">
      <img src="<?= $product->image; ?>" height="200" width="390"  data-toggle="modal" data-target="#image"/>
    </div>

  </div>
  <div class="col-md-8 col-sm-6">

    <h5 class="page-title-sub">Ürün Bilgileri</h5>

    <table class="table table-striped table-bordered table-hover">
      <tbody>
        <tr>
          <td><b>Kategori</b></td>
          <td><?= $product->producttype->category->name; ?></td>
        </tr>
        <tr>
          <td><b>Ürün Tipi</b></td>
          <td><?= $product->producttype->name; ?></td>
        </tr>
        <tr>
          <td><b>Ad</b></td>
          <td><?= $product->name; ?></td>
        </tr>
        <tr>
          <td><b>Fiyat</b></td>
          <td><?= $product->price; ?></td>
        </tr>
      </tbody>
    </table>

  </div>
</div>


<div class="container">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#content" aria-controls="content" role="tab" data-toggle="tab">Detaylı Açıklama</a></li>
    <li role="presentation"><a href="#file" aria-controls="file" role="tab" data-toggle="tab">Dosya</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="content">
      <?= $product->content; ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="file">
      <embed src="<?= $product->file; ?>" style="width:100%;height:200px"></embed>
    </div>
  </div>

</div>

<script type="text/javascript">
$('#someTab').tab('show');
</script>
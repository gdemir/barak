<?php $product = Product::find($id); ?>

<center>

  <img src="<?= $product->image; ?>" height="120" width="120" class="img-circle" data-toggle="modal" data-target="#photo"/>
  <div class="modal fade" id="photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <img src="<?= $product->image; ?>" width="600" height="400" class="img-thumbnail" />
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top:30px">
    <h1><?= $product->name; ?></h1>
  </div>

</center>

<h5 class="page-title-sub">Genel Özellikleri</h5>

<div class="list-group">
  <a href="#" class="list-group-item">First item</a>
  <a href="#" class="list-group-item">Second item</a>
  <a href="#" class="list-group-item">Third item</a>
</div>
<div class="container">

  <div class="row">
    <?php foreach ($products as $product) { ?>

    <div class="col-sm-6 col-md-4 animated swing">
      <div class="thumbnail">
        <img src="<?= $product->image; ?>" alt="..." style="height:100px; width:100%">
        <div class="caption">
          <h3><?= $product->name; ?></h3>
          <p><?= $product->name; ?></p>
          <p><a href="/home/productpage/show/<?= $product->id; ?>" class="btn btn-primary fa fa-search" role="button">İncele</a></p>
        </div>
      </div>
    </div>

    <?php } ?>
  </div>

</div>
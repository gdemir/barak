<div class="container">

  <div class="row">
    <?php foreach ($producttypes as $producttype) { ?>

    <div class="col-sm-6 col-md-4 animated swing">
      <div class="thumbnail">
        <img src="<?= $producttype->image; ?>" alt="..." style="height:100px; width:100%">
        <div class="caption">
          <h3><?= $producttype->name; ?></h3>
          <p><?= $producttype->name; ?></p>
          <p><a href="/home/productpage/show/<?= $producttype->id; ?>" class="btn btn-primary fa fa-search" role="button">İncele</a></p>
        </div>
      </div>
    </div>

    <?php } ?>
  </div>

</div>
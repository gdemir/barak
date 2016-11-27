<div class="container">

  <div class="row">
    <?php foreach ($categories as $category) { ?>

    <div class="col-sm-6 col-md-4 animated swing">
      <div class="thumbnail">
        <img src="<?= $category->image; ?>" alt="..." style="height:100px; width:100%">
        <div class="caption">
          <h3><?= $category->name; ?></h3>
          <p><?= $category->content; ?></p>
          <p><a href="/home/categorypage/show/<?= $category->id; ?>" class="btn btn-primary fa fa-search" role="button">İncele</a></p>
        </div>
      </div>
    </div>

    <?php } ?>
  </div>

</div>
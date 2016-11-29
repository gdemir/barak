<div class="container">

  <div class="row">
    <?php foreach ($products as $product) { ?>

    <div class="col-sm-6 col-md-4 animated swing">
      <div class="thumbnail">
        <img src="<?= $product->image; ?>" alt="..." style="height:100px; width:100%">
        <div class="caption">
          <h3><?= $product->name; ?></h3>
          <p><?= $product->name; ?></p>
          <p><?= $product->price; ?></p>
          <p><a href="/home/product/show/<?= $product->id; ?>" class="btn btn-primary fa fa-search" role="button">İncele</a></p>
        </div>
      </div>
    </div>

    <?php } ?>
  </div>

</div>

<nav aria-label="Page navigation">
  <ul class="pagination">
    <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
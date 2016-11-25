
<!-- first section - Home -->
<div class="parallax" style="background: url(<?= $category->image; ?>) no-repeat center fixed">
  <div class="parallax-caption">
    <h1><?= $category->name; ?></h1>
  </div>
</div>
<!-- /first section -->

<div class="container">

  <h4 class="page-title">Ürün Tipleri</h4>

  <table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th></th>
        <th>Ad</th>
        <th>İçerik</th>
        <th>Çeşit</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if ($producttypes) { ?>
      <?php foreach ($producttypes as $producttype) { ?>
      <tr>
        <td><img src="<?= $producttype->category->image ?>" style="width:200px"></td>
        <td><?= $producttype->name ?></td>
        <td><?= $producttype->content ?></td>
        <td><?= Product::load()->where(["producttype_id" => $producttype->id])->count(); ?></td>
        <td>
          <a href="/home/producttypepage/show/<?= $producttype->id; ?>"
            class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i>Göster
          </a>

        </form>
      </td>
    </tr>

    <?php } ?>
    <?php } else { ?>
    <tr class="text-center"><td colspan="4">Henüz Ürün Tipi mevcut değil</td></tr>
    <?php } ?>
  </tbody>
</table>


</div>
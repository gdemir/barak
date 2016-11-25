<h4 class="page-title">Ürünler</h4>

<table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>İd</th>
      <th>Kategori</th>
      <th>Ürün Tipi</th>
      <th>Ürün</th>
      <th>Özellik</th>
      <th>Oluştu</th>
      <th>Düzenlendi</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($productfeatures) { ?>
    <?php foreach ($productfeatures as $productfeature) { ?>

    <tr>
      <td><?= $productfeature->id; ?></td>
      <td><?= $productfeature->product->producttype->category->name; ?></td>
      <td><?= $productfeature->product->producttype->name; ?></td>
      <td><?= $productfeature->product->name; ?></td>
      <td><?= $productfeature->name; ?></td>
      <td><?= $productfeature->created_at; ?></td>
      <td><?= $productfeature->updated_at; ?></td>

      <td>
        <form action="/admin/productfeature/destroy" method="post">
          <a href="/admin/productfeature/show/<?= $productfeature->id; ?>"
            class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i>
          </a>

          <a href="/admin/productfeature/edit/<?= $productfeature->id; ?>"
            class="btn btn-default" role="button" title="Düzenle"><i class="fa fa-edit"></i>
          </a>

          <input type="hidden" value="<?= $productfeature->id; ?>" id="id" name="id"/>
          <button type="submit" class="btn btn-default"
          onClick=\"return confirm('Bu kaydı silmek istediğinizden emin misiniz?');\" title="Sil">
          <i class="fa fa-trash"></i>
        </button>
      </form>
    </td>
  </tr>

  <?php } ?>
  <?php } else { ?>
  <tr class="text-center"><td colspan="4">Henüz Ürün mevcut değil</td></tr>
  <?php } ?>
</tbody>
</table>
<a class="btn btn-primary" href="/admin/productfeature/new">Ürün Ekle</a>
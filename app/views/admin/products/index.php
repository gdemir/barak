<h4 class="page-title">Ürünler</h4>

<table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>İd</th>
      <th>Kategori</th>
      <th>Ürün Tipi</th>
      <th>Ürün</th>
      <th>Oluştu</th>
      <th>Düzenlendi</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($products) { ?>
    <?php foreach ($products as $product) { ?>

    <tr>
      <td><?= $product->id; ?></td>
      <td><?= $product->producttype->category->name; ?></td>
      <td><?= $product->producttype->name; ?></td>
      <td><?= $product->name; ?></td>
      <td><?= $product->created_at; ?></td>
      <td><?= $product->updated_at; ?></td>

      <td>
        <form action="/admin/products/destroy" method="post">
          <a href="/admin/product/show/<?= $product->id; ?>"
            class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i>
          </a>

          <a href="/admin/products/edit/<?= $product->id; ?>"
            class="btn btn-default" role="button" title="Düzenle"><i class="fa fa-edit"></i>
          </a>

          <input type="hidden" value="<?= $product->id; ?>" id="id" name="id"/>
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
<a class="btn btn-primary" href="/admin/products/create">Ürün Ekle</a>
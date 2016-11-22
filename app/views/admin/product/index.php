<h4 class="page-title">Ürünler</h4>

<table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>İd</th>
      <th>name</th>
      <th>İçerik</th>
      <th>Oluştu</th>
      <th>Düzenlendi</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $product) { ?>

    <tr>
      <td><?= $product->id ?></td>
      <td><?= $product->name ?></td>
      <td><?= $product->content ?></td>
      <td><?= $product->created_at ?></td>
      <td><?= $product->updated_at ?></td>

      <td>
        <form action="/admin/product/destroy" method="post">
          <a href="/admin/product/show/<?= $product->id; ?>"
            class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i>
          </a>

          <a href="/admin/product/edit/<?= $product->id; ?>"
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
</tbody>
</table>
<a class="btn btn-primary" href="/admin/product/new">Ürün Ekle</a>
<h4 class="page-title">Ürün Tipleri</h4>

<table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>İd</th>
      <th>Kategori</th>
      <th>Ad</th>
      <th>İçerik</th>
      <th>Oluştu</th>
      <th>Düzenlendi</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($producttypes) { ?>
    <?php foreach ($producttypes as $producttype) { ?>

    <tr>
      <td><?= $producttype->id; ?></td>
      <td><?= $producttype->category->name; ?></td>
      <td><?= $producttype->name; ?></td>
      <td><?= $producttype->content; ?></td>
      <td><?= $producttype->created_at; ?></td>
      <td><?= $producttype->updated_at; ?></td>

      <td>
        <form action="/admin/producttypes/destroy" method="post">
          <a href="/admin/producttypes/show/<?= $producttype->id; ?>"
            class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i>
          </a>

          <a href="/admin/producttypes/edit/<?= $producttype->id; ?>"
            class="btn btn-default" role="button" title="Düzenle"><i class="fa fa-edit"></i>
          </a>

          <input type="hidden" value="<?= $producttype->id; ?>" id="id" name="id"/>
          <button type="submit" class="btn btn-default"
          onClick=\"return confirm('Bu kaydı silmek istediğinizden emin misiniz?');\" title="Sil">
          <i class="fa fa-trash"></i>
        </button>
      </form>
    </td>
  </tr>

  <?php } ?>
  <?php } else { ?>
  <tr class="text-center"><td colspan="4">Henüz Ürün Tipi mevcut değil</td></tr>
  <?php } ?>
</tbody>
</table>
<a class="btn btn-primary" href="/admin/producttypes/create">Ürün Tipi Ekle</a>
<h4 class="page-title">Kategoriler</h4>

<table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>İd</th>
      <th>Ad</th>
      <th>İçerik</th>
      <th>Oluştu</th>
      <th>Düzenlendi</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($categories) { ?>
    <?php foreach ($categories as $category) { ?>

    <tr>
      <td><?= $category->id ?></td>
      <td><?= $category->name ?></td>
      <td><?= $category->content ?></td>
      <td><?= $category->created_at ?></td>
      <td><?= $category->updated_at ?></td>

      <td>
        <form action="/admin/categories/destroy" method="post">
          <a href="/admin/category/show/<?= $category->id; ?>"
            class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i>
          </a>

          <a href="/admin/categories/edit/<?= $category->id; ?>"
            class="btn btn-default" role="button" title="Düzenle"><i class="fa fa-edit"></i>
          </a>

          <input type="hidden" value="<?= $category->id; ?>" id="id" name="id"/>
          <button type="submit" class="btn btn-default"
          onClick=\"return confirm('Bu kaydı silmek istediğinizden emin misiniz?');\" title="Sil">
          <i class="fa fa-trash"></i>
        </button>
      </form>
    </td>
  </tr>

  <?php } ?>
  <?php } else { ?>
  <tr class="text-center"><td colspan="4">Henüz Kategori mevcut değil</td></tr>
  <?php } ?>
</tbody>
</table>
<a class="btn btn-primary" href="/admin/categories/create">Kategori Ekle</a>
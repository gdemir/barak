<h4 class="page-title">Personeller</h4>

<table id="example" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Ad</th>
      <th>Soyad</th>
      <th>Kullanıcı Adı</th>
      <th>Telefon</th>
      <th>Email</th>
      <th>Admin</th>
      <th>Patron</th>
      <th>Oluştu</th>
      <th>Düzenlendi</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  	<?php if ($users) { ?>
    <?php foreach ($users as $user) { ?>
    <tr>
      <td><?= $user->first_name; ?></td>
      <td><?= $user->last_name; ?></td>
      <td><?= $user->username; ?></td>
      <td><?= $user->phone; ?></td>
      <td><?= $user->email; ?></td>
      <td><?= ($user->admin) ? "Evet" : "Hayır"; ?></td>
      <td><?= ($user->boss) ? "Evet" : "Hayır"; ?></td>
      <td><?= $user->created_at; ?></td>
      <td><?= $user->updated_at; ?></td>
      <td>
        <form action="/admin/users/destroy" method="post">
          <a href="/admin/users/show/<?= $user->id; ?>" class="btn btn-default" role="button" title="Göster"><i class="fa fa-search"></i></a>
          <a href="/admin/users/edit/<?= $user->id; ?>" class="btn btn-default" role="button" title="Düzenle"><i class="fa fa-edit"></i></a>

          <input type="hidden" value="<?= $user->id; ?>" id="id" name="id" />
          <button type="submit" class="btn btn-default"
          onClick=\"return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');\" title="Sil">
          <i class="fa fa-trash"></i>
        </button>
      </form>
    </td>
  </tr>

  <?php } ?>
  <?php } else { ?>
  <tr class="text-center"><td colspan="4">Henüz Kullanıcı mevcut değil</td></tr>
  <?php } ?>
</tbody>
</table>
<a class="btn btn-primary" href="/admin/users/create">Kullanıcı Ekle</a>

<link rel="stylesheet" href="assets/css/style.css">

<div class="header">DATA PELANGGAN</div>

<div class="container">
<div class="card">

<a href="index.php" class="btn">+ Tambah Data</a>

<br><br>

<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Layanan</th>
    <th>Aksi</th>
</tr>

<?php $no = 1; foreach($data as $row): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['layanan'] ?></td>
    <td class="action">
        <button onclick="openEditModal('<?= $row['id'] ?>','<?= $row['nama'] ?>','<?= $row['layanan'] ?>')" class="edit">Edit</button>
        <a href="index.php?action=delete&id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>
</div>

<!-- MODAL EDIT -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Data</h3>

        <form method="POST" action="index.php?action=update">
            <input type="hidden" name="id" id="edit_id">

            <input type="text" name="nama" id="edit_nama"><br><br>
            <input type="text" name="layanan" id="edit_layanan"><br><br>

            <button type="submit">Update</button>
            <button type="button" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, nama, layanan) {
    document.getElementById('editModal').style.display = 'flex';

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_layanan').value = layanan;
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
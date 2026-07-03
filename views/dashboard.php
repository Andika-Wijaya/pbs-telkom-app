<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Data Pelanggan</h2>

<a href="index.php">+ Tambah</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Layanan</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($data as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['layanan'] ?></td>
        <td>
            <button onclick="openEdit('<?= $row['id'] ?>','<?= $row['nama'] ?>','<?= $row['layanan'] ?>')">Edit</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- MODAL -->
<div id="modalEdit" style="display:none; position:fixed; top:20%; left:35%; background:#fff; padding:20px; border:1px solid #000;">
    
    <h3>Edit Data</h3>

    <form id="formEdit">
        <input type="hidden" name="id" id="edit_id">

        <input type="text" name="nama" id="edit_nama" required><br><br>

        <select name="layanan" id="edit_layanan">
            <option value="Indihome">Indihome</option>
            <option value="Wifi">Wifi</option>
        </select><br><br>

        <button type="submit">Update</button>
        <button type="button" onclick="closeModal()">Batal</button>
    </form>
</div>

<script>
function openEdit(id, nama, layanan) {
    document.getElementById("modalEdit").style.display = "block";
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_layanan").value = layanan;
}

function closeModal() {
    document.getElementById("modalEdit").style.display = "none";
}

// AJAX UPDATE
document.getElementById("formEdit").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("index.php?action=update", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(() => {
        alert("Data berhasil diupdate");
        location.reload();
    });
});
</script>

</body>
</html>
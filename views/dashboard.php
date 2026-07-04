<?php


// 🔐 Proteksi login
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="header">DATA PELANGGAN</div>

<p>
    Selamat datang, 
    <b><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></b> 👋
</p>

<div class="container">
    <div class="card">

        <a href="index.php?action=logout">Logout</a>
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
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['layanan']) ?></td>
                <td class="action">
                    <button 
                        onclick="openEditModal('<?= $row['id'] ?>','<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>','<?= htmlspecialchars($row['layanan'], ENT_QUOTES) ?>')" 
                        class="edit">
                        Edit
                    </button>

                    <button onclick="hapusData(<?= $row['id'] ?>)">Hapus</button>
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

            <input type="text" name="nama" id="edit_nama" required><br><br>
            <input type="text" name="layanan" id="edit_layanan" required><br><br>

            <button type="submit">Update</button>
            <button type="button" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>

<script>
// buka modal edit
function openEditModal(id, nama, layanan) {
    document.getElementById('editModal').style.display = 'flex';

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_layanan').value = layanan;
}

// tutup modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// hapus data (AJAX)
function hapusData(id) {
    if(confirm("Yakin mau hapus data?")) {
        fetch("index.php?action=delete&id=" + id)
        .then(res => res.text())
        .then(() => {
            alert("Data berhasil dihapus");
            location.reload();
        });
    }
}
</script>
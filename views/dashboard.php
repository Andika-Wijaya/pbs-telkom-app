<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔐 Proteksi login
if (!isset($_SESSION['login'])) {
    header("Location: index.php?action=login");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="header">DATA PELANGGAN</div>

<p>
    Selamat datang, 
    <b><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></b> 👋
</p>

<div class="container">
    <div class="card">

        <a href="#" onclick="logoutConfirm()">Logout</a>

        <input type="text" id="search" placeholder="Cari nama / layanan..." style="margin-bottom:10px;">

        <a href="index.php" class="btn">+ Tambah Data</a>

        <br><br>

        <table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Layanan</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody id="tableBody">
        <?php $no = 1; foreach($data as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['layanan']) ?></td>
            <td class="action">
                <button onclick="openEditModal('<?= $row['id'] ?>','<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>','<?= htmlspecialchars($row['layanan'], ENT_QUOTES) ?>')" class="edit">
                    Edit
                </button>

                <button onclick="hapusData(<?= $row['id'] ?>)">Hapus</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
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
    document.getElementById("search").addEventListener("keyup", function() {
    let keyword = this.value;

    fetch("index.php?action=searchAjax&keyword=" + keyword)
    .then(res => res.json())
    .then(data => {

        let html = "";

        if (data.length === 0) {
            html = `<tr><td colspan="4">Data tidak ditemukan</td></tr>`;
        } else {
            let no = 1;
            data.forEach(row => {
                html += `
                <tr>
                    <td>${no++}</td>
                    <td>${row.nama}</td>
                    <td>${row.layanan}</td>
                    <td>
                        <button onclick="openEditModal('${row.id}','${row.nama}','${row.layanan}')">Edit</button>
                        <button onclick="hapusData(${row.id})">Hapus</button>
                    </td>
                </tr>
                `;
            });
        }

        document.getElementById("tableBody").innerHTML = html;
    });
});
// ================= MODAL =================
function openEditModal(id, nama, layanan) {
    document.getElementById('editModal').style.display = 'flex';

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_layanan').value = layanan;
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// ================= HAPUS DATA =================
function hapusData(id) {
    Swal.fire({
        title: 'Yakin?',
        text: "Data akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("index.php?action=delete&id=" + id)
            .then(res => res.text())
            .then(() => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            });
        }
    });
}

function logoutConfirm() {
    Swal.fire({
        title: 'Logout?',
        text: "Kamu akan keluar dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "controllers/logout.php";
        }
    });
}
</script>

<!-- ================= POPUP LOGIN SUCCESS ================= -->
<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    title: 'Login Berhasil!',
    text: 'Selamat datang, <?= $_SESSION['username']; ?> 👋',
    icon: 'success',
    showConfirmButton: false,
    timer: 2000
});
</script>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

</body>
</html>
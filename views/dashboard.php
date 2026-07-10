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
<div style="margin-bottom:15px;">
    <a href="index.php?action=dashboard">🏠 Dashboard</a> |
    <a href="index.php?action=pelanggan">👥 Data Pelanggan</a> |
    <a href="#" onclick="logoutConfirm()">🚪 Logout</a>
</div>
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
    <th>No Internet</th>
    <th>Nama</th>
    <th>No Tlp</th>
    <th>Layanan</th>
    <th>Harga</th>
    <th>Tagihan</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
    </thead>

    <tbody id="tableBody">
        <?php $no = 1; foreach($data as $row): ?>
        <tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['no_internet']) ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['no_tlp']) ?></td>
    <td><?= htmlspecialchars($row['layanan']) ?></td>
    <td>Rp <?= number_format($row['harga']) ?></td>

    <!-- TAGIHAN -->
    <td>
        <?php if ($row['tagihan'] == 'lunas'): ?>
            <span style="color:green;">Lunas</span>
        <?php else: ?>
            <span style="color:red;">Belum Bayar</span>
        <?php endif; ?>
    </td>

    <!-- STATUS -->
    <td>
        <?php if ($row['status'] == 'aktif'): ?>
            <span style="color:green;">Aktif</span>
        <?php elseif ($row['status'] == 'pending'): ?>
            <span style="color:orange;">Pending</span>
        <?php else: ?>
            <span style="color:red;">Terisolir</span>
        <?php endif; ?>
    </td>

    <td class="action">
        <button onclick="openEditModal(
    '<?= $row['id'] ?>',
    '<?= $row['no_internet'] ?>',
    '<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>',
    '<?= $row['no_tlp'] ?>',
    '<?= htmlspecialchars($row['layanan'], ENT_QUOTES) ?>',
    '<?= $row['harga'] ?>',
    '<?= $row['tagihan'] ?>',
    '<?= $row['status'] ?>'
)">
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

            <input type="text" name="no_internet" id="edit_no_internet" placeholder="No Internet" required><br><br>
            <input type="text" name="nama" id="edit_nama" placeholder="Nama" required><br><br>
            <input type="text" name="no_tlp" id="edit_no_tlp" placeholder="No Tlp" required><br><br>
            <input type="text" name="layanan" id="edit_layanan" placeholder="Layanan" required><br><br>
            <input type="number" name="harga" id="edit_harga" placeholder="Harga" required><br><br>

            <select name="tagihan" id="edit_tagihan">
                <option value="lunas">Lunas</option>
                <option value="belum bayar">Belum Bayar</option>
            </select><br><br>

            <select name="status" id="edit_status">
                <option value="aktif">Aktif</option>
                <option value="pending">Pending</option>
                <option value="terisolir">Terisolir</option>
            </select><br><br>

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
        <td>${row.no_internet}</td>
        <td>${row.nama}</td>
        <td>${row.no_tlp}</td>
        <td>${row.layanan}</td>
        <td>Rp ${row.harga}</td>
        <td>${row.tagihan}</td>
        <td>${row.status}</td>
        <td>
            <button onclick="openEditModal(
                '${row.id}',
                '${row.no_internet}',
                '${row.nama}',
                '${row.no_tlp}',
                '${row.layanan}',
                '${row.harga}',
                '${row.tagihan}',
                '${row.status}'
            )">Edit</button>

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
function openEditModal(id, no_internet, nama, no_tlp, layanan, harga, tagihan, status) {
    document.getElementById('editModal').style.display = 'flex';

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_no_internet').value = no_internet;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_no_tlp').value = no_tlp;
    document.getElementById('edit_layanan').value = layanan;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('edit_tagihan').value = tagihan;
    document.getElementById('edit_status').value = status;
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
<?php
if (!isset($_SESSION['login'])) {
    header("Location: index.php?action=login");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background: #d32f2f;
            color: white;
            padding: 15px;
        }

        .navbar a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            padding: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            width: 250px;
            margin-right: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin: 0;
            font-size: 28px;
            color: #d32f2f;
        }

        .card p {
            margin: 5px 0 0;
        }

        .welcome {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="index.php?action=dashboard">🏠 Dashboard</a>
    <a href="index.php?action=pelanggan">👥 Data Pelanggan</a>
    <a href="#" onclick="logoutConfirm()">🚪 Logout</a>
</div>

<div class="container">

    <!-- WELCOME -->
    <div class="welcome">
        Halo, <b><?= $_SESSION['username']; ?></b> 👋
    </div>

    <!-- NOTIF LOGIN -->
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color:green; margin-top:10px;">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- CARD STATS -->
    <div style="margin-top:20px;">

        <div class="card">
            <h2><?= $total; ?></h2>
            <p>Total Pelanggan</p>
        </div>

        <div class="card">
            <h2><?= $aktif; ?></h2>
            <p>Pelanggan Aktif</p>
        </div>

    </div>

    <!-- INFO TAMBAHAN -->
    <div style="margin-top:30px;">
        <h3>📊 Informasi</h3>
        <p>
            Sistem manajemen pelanggan Indibiz / Telkom.<br>
            Gunakan menu <b>Data Pelanggan</b> untuk mengelola data.
        </p>
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
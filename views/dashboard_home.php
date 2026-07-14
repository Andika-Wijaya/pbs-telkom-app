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
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="page-shell">
    <div class="topbar">
        <div class="brand">Telkom Customer Management</div>
        <div class="nav-links">
            <a class="active" href="index.php?action=dashboard">🏠 Dashboard</a>
            <a href="index.php?action=pelanggan">👥 Data Pelanggan</a>
            <a href="#" onclick="logoutConfirm()">🚪 Logout</a>
        </div>
    </div>

    <div class="page-header">
        <div>
            <p class="eyebrow">Ringkasan</p>
            <h1>Dashboard</h1>
            <p>Informasi ringkas mengenai data pelanggan di sistem.</p>
        </div>
        <div class="header-chip">Halo, <b><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></b> 👋</div>
    </div>

    <div class="container">
        <div class="panel">
            <?php if (isset($_SESSION['success'])): ?>
                <script>
                Swal.fire({
                    title: 'Login Berhasil!',
                    text: '<?= htmlspecialchars($_SESSION['success'], ENT_QUOTES); ?>',
                    icon: 'success',
                    timer: 1800,
                    showConfirmButton: false
                });
                </script>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="panel-toolbar">
                <div>
                    <h3 style="margin: 0 0 4px;">Statistik Pelanggan</h3>
                    <p style="margin: 0; color: #64748b;">Pantau jumlah pelanggan secara cepat.</p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div class="card" style="padding: 20px; border: 1px solid #f1f5f9; border-radius: 16px;">
                    <h2 style="margin: 0; font-size: 32px; color: var(--primary);"><?= $total; ?></h2>
                    <p style="margin: 8px 0 0; color: #64748b;">Total Pelanggan</p>
                </div>
                <div class="card" style="padding: 20px; border: 1px solid #f1f5f9; border-radius: 16px;">
                    <h2 style="margin: 0; font-size: 32px; color: #2563eb;"><?= $aktif; ?></h2>
                    <p style="margin: 8px 0 0; color: #64748b;">Pelanggan Aktif</p>
                </div>
            </div>

            <div style="margin-top: 24px; padding: 18px; background: #fff7f7; border-radius: 14px; border: 1px solid #fde2e2;">
                <h3 style="margin: 0 0 8px;">Informasi</h3>
                <p style="margin: 0; color: #64748b;">
                    Gunakan menu <b>Data Pelanggan</b> untuk menambah, mengedit, dan menghapus data pelanggan dengan lebih nyaman.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function logoutConfirm() {
    Swal.fire({
        title: 'Logout?',
        text: 'Kamu akan keluar dari sistem!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'controllers/logout.php';
        }
    });
}
</script>
</body>
</html>
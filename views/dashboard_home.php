<?php
if (!isset($_SESSION['login'])) {
    header("Location: index.php?action=login");
    exit;
}
require_once __DIR__ . '/../config/layanan.php';
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
        <div class="brand">
            <?php
            $logo_path = null;
            $logo_candidates = [
                __DIR__ . '/../assets/img/telkom.png',
                __DIR__ . '/../assets/img/logo.png',
                __DIR__ . '/../assets/img/logo.jpg',
                __DIR__ . '/../assets/img/logo.jpeg',
                __DIR__ . '/../assets/img/logo.jfif',
            ];
            foreach ($logo_candidates as $candidate) {
                if (file_exists($candidate)) {
                    $logo_path = str_replace(__DIR__ . '/../', '', $candidate);
                    break;
                }
            }
            ?>
            <?php if ($logo_path): ?>
                <img src="<?= htmlspecialchars($logo_path); ?>" alt="Telkom" style="width:36px;height:36px;object-fit:contain;">
            <?php else: ?>
                <span class="logo" aria-hidden="true">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="24" height="24" rx="6" fill="#d32f2f"/>
                        <path d="M6 12h12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 6v12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            <?php endif; ?>
            Telkom Customer Management
        </div>
        <div class="nav-links">
            <a class="active" href="index.php?action=dashboard">🏠 Dashboard</a>
            <a href="index.php?action=pelanggan">👥 Data Pelanggan</a>
            <a href="#" onclick="logoutConfirm(event)">🚪 Logout</a>
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
                <div class="card" style="padding: 20px; border: 1px solid #f1f5f9; border-radius: 16px;">
                    <h2 style="margin: 0; font-size: 32px; color: #16a34a;"><?= $today_new ?? 0; ?></h2>
                    <p style="margin: 8px 0 0; color: #64748b;">Pelanggan Baru Hari Ini</p>
                </div>
            </div>

            <div style="margin-top: 24px; padding: 18px; background: #fff7f7; border-radius: 14px; border: 1px solid #fde2e2;">
                <h3 style="margin: 0 0 8px;">Informasi</h3>
                <p style="margin: 0; color: #64748b;">
                    Gunakan menu <b>Data Pelanggan</b> untuk menambah, mengedit, dan menghapus data pelanggan dengan lebih nyaman.
                </p>
            </div>
            
            <div style="margin-top:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <h3 style="margin:0">Layanan IndiBiz</h3>
                    
                </div>
                <div style="background:#fff; border-radius:8px; padding:12px; border:1px solid #eef2f7;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="text-align:left; color:#334155;">
                                <th style="padding:8px">No</th>
                                <th style="padding:8px">Kode</th>
                                <th style="padding:8px">Nama Layanan</th>
                                <th style="padding:8px">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($layananList)): ?>
                            <?php $i=1; foreach($layananList as $l): ?>
                            <tr>
                                <td style="padding:8px"><?= $i++; ?></td>
                                <td style="padding:8px"><?= htmlspecialchars($l['kode']); ?></td>
                                <td style="padding:8px"><?= htmlspecialchars($l['nama']); ?></td>
                                <td style="padding:8px">Rp <?= number_format($l['harga']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="padding:12px; color:#64748b;">Belum ada layanan.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<footer style="margin-top:24px; text-align:center; color:#94a3b8; padding:18px 0;">
    &copy; <?= date('Y'); ?> Telkom Customer Management — dibuat dengan PHP
</footer>

<script>
function logoutConfirm(e) {
    if (e && e.preventDefault) e.preventDefault();
    Swal.fire({
        title: 'Logout?',
        text: 'Kamu akan keluar dari sistem!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php?action=logout';
        }
    });
}
</script>
</body>
</html>
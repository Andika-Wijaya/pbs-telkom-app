<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['login'])) {
    header("Location: index.php?action=dashboard");
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h2>Masuk ke Sistem</h2>
        <p>Silakan login untuk mengakses data pelanggan.</p>

        <?php if ($error): ?>
        <script>
        Swal.fire({
            title: 'Login Gagal!',
            text: '<?= htmlspecialchars($error, ENT_QUOTES) ?>',
            icon: 'error'
        });
        </script>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
        <script>
        Swal.fire({
            title: 'Logout Berhasil!',
            text: 'Kamu sudah keluar dari sistem.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        </script>
        <?php endif; ?>

        <form method="POST" action="index.php?action=loginProcess" class="form-grid">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button class="btn btn-primary" type="submit" name="login">Login</button>
        </form>

        <p style="margin-top: 14px; font-size: 0.9rem; color: #64748b;">Default login: <b>admin / admin123</b></p>
    </div>
</div>
</body>
</html>
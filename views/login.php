<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

$db = new Database();
$conn = $db->connect();

$userModel = new User($conn);

$error = "";

// kalau sudah login
if (isset($_SESSION['login'])) {
    header("Location: /pbs-telkom-app/index.php?action=dashboard");
    exit;
}

if (isset($_POST['login'])) {

    // 🔥 bersihkan notif lama
    unset($_SESSION['success']);

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = $userModel->getUserByUsername($username);

    if ($user) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['success'] = "Login berhasil!";

            header("Location: /pbs-telkom-app/index.php?action=dashboard");
            exit;

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<h2>Login</h2>

<!-- ❌ ERROR LOGIN -->
<?php if ($error): ?>
<script>
Swal.fire({
    title: 'Login Gagal!',
    text: '<?= $error ?>',
    icon: 'error'
});
</script>
<?php endif; ?>

<!-- ✅ LOGOUT SUCCESS -->
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

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
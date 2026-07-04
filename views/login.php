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
$success = "";

// Kalau sudah login, langsung ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: ../index.php?action=dashboard");
    exit;
}

if (isset($_POST['login'])) {
    // Amankan input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Ambil user dari database
    $user = $userModel->getUserByUsername($username);

    // Debug (aktifkan kalau perlu)
    // var_dump($user); die();

    if ($user) {
       
        // Verifikasi password hash
        if (password_verify($password, $user['password'])) {

            // Simpan session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['username'];

            $_SESSION['success'] = "Login berhasil!";

            header("Location: ../index.php?action=dashboard");
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
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
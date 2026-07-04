<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Start ulang untuk kirim pesan
session_start();
$_SESSION['success'] = "Logout berhasil!";

// Redirect ke login
header("Location: ../views/login.php");
exit;
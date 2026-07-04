<?php
session_start();

// Hapus semua session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Redirect ke login + kirim notifikasi
header("Location: /pbs-telkom-app/index.php?action=login&logout=success");
exit;
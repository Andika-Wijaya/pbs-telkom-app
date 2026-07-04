<?php
session_start();
require_once '../config/Database.php';

$db = new Database();
$conn = $db->connect();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE username=?");
    $query->execute([$username]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Login gagal";
    }
}
?>
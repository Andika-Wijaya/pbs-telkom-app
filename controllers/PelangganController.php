<?php
session_start();
require_once __DIR__ . '/../models/User.php';

class PelangganController {

    public function index() {
        include 'views/index.php';
    }

    public function dashboard() {

    // 🔐 CEK LOGIN DI SINI
    if (!isset($_SESSION['login'])) {
        header("Location: index.php");
        exit;
    }

    $user = new User();
    $data = $user->getAll();

    require "views/dashboard.php";
}

    public function create() {
        $user = new User();
        $user->nama = $_POST['nama'];
        $user->layanan = $_POST['layanan'];
        $user->create();

        header("Location: index.php?action=dashboard");
        exit;
    }

    public function delete() {
        $user = new User();
        $user->id = $_GET['id'];
        $user->delete();

        header("Location: index.php?action=dashboard");
        exit;
    }

    public function edit() {
        $user = new User();
        $user->id = $_GET['id'];
        $data = $user->getById();

        include 'views/edit.php';
    }

    public function update() {
        $user = new User();
        $user->id = $_POST['id'];
        $user->nama = $_POST['nama'];
        $user->layanan = $_POST['layanan'];
        $user->update();

        header("Location: index.php?action=dashboard");
        exit;
    }

    public function search() {
        $user = new User();
        $keyword = $_GET['keyword'];
        $stmt = $user->search($keyword);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/dashboard.php';
    }

    public function login() {
    require "views/login.php";}
    public function loginProcess() {
    $user = new User();
    $data = $user->login($_POST['username'], $_POST['password']);

    if ($data) {
        $_SESSION['login'] = true;
        header("Location: index.php?action=dashboard");
    } else {
        echo "Login gagal!";
    }
}

public function logout() {
    session_destroy();
    header("Location: index.php");
}
}
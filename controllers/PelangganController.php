<?php

require_once __DIR__ . '/../models/User.php';

class PelangganController {

private $conn;

public function __construct() {
        require_once 'config/Database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }

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
    $keyword = $_GET['keyword'] ?? '';

    $query = "SELECT * FROM pelanggan WHERE nama LIKE ? OR layanan LIKE ?";
    $stmt = $this->conn->prepare($query);

    $search = "%$keyword%";
    $stmt->execute([$search, $search]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require 'views/dashboard.php';
}

public function searchAjax() {
    $keyword = $_GET['keyword'] ?? '';

    $stmt = $this->conn->prepare("
        SELECT * FROM pelanggan 
        WHERE nama LIKE ? OR layanan LIKE ?
    ");

    $search = "%$keyword%";
    $stmt->execute([$search, $search]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // kirim JSON (bukan view)
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

    public function login() {
    require "views/login.php";}

    public function loginProcess() {
    $user = new User();
    $data = $user->login($_POST['username'], $_POST['password']);

    if ($data) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['username'];
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
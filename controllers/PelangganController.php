<?php

require_once __DIR__ . '/../models/User.php';

class PelangganController {

    private $conn;

    public function __construct() {
        require_once 'config/Database.php';
        $db = new Database();
        $this->conn = $db->connect();
    }

    // ================= GET ALL =================
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM pelanggan ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ================= CREATE =================
    public function create() {
        $stmt = $this->conn->prepare("
            INSERT INTO pelanggan 
            (no_internet, nama, no_tlp, layanan, harga, tagihan, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_POST['no_internet'],
            $_POST['nama'],
            $_POST['no_tlp'],
            $_POST['layanan'],
            $_POST['harga'],
            $_POST['tagihan'],
            $_POST['status']
        ]);

        header("Location: index.php?action=pelanggan");
        exit;
    }

    // ================= UPDATE =================
    public function update() {
        $stmt = $this->conn->prepare("
            UPDATE pelanggan SET
                no_internet=?,
                nama=?,
                no_tlp=?,
                layanan=?,
                harga=?,
                tagihan=?,
                status=?
            WHERE id=?
        ");

        $stmt->execute([
            $_POST['no_internet'],
            $_POST['nama'],
            $_POST['no_tlp'],
            $_POST['layanan'],
            $_POST['harga'],
            $_POST['tagihan'],
            $_POST['status'],
            $_POST['id']
        ]);

        header("Location: index.php?action=pelanggan");
        exit;
    }

    // ================= DELETE =================
    public function delete() {
        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id=?");
        $stmt->execute([$_GET['id']]);
        exit;
    }

    // ================= SEARCH AJAX =================
    public function searchAjax() {
        $keyword = $_GET['keyword'] ?? '';

        $stmt = $this->conn->prepare("
            SELECT * FROM pelanggan 
            WHERE nama LIKE ? OR layanan LIKE ?
        ");

        $search = "%$keyword%";
        $stmt->execute([$search, $search]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ================= LOGIN =================
    public function login() {
        require "views/login.php";
    }

    public function loginProcess() {
        $user = new User();
        $data = $user->login($_POST['username'], $_POST['password']);

        if ($data) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['success'] = "Login berhasil!";
            header("Location: index.php?action=dashboard");
        } else {
            $_SESSION['error'] = "Login gagal!";
            header("Location: index.php?action=login");
        }
    }

    // ================= STATS =================
    public function getStats() {
        $total = $this->conn->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
        $aktif = $this->conn->query("SELECT COUNT(*) FROM pelanggan WHERE status='aktif'")->fetchColumn();

        return [
            'total' => $total,
            'aktif' => $aktif
        ];
    }

    // ================= LOGOUT =================
    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
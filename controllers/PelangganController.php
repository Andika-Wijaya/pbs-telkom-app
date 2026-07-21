<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../lib/Validation.php';

class PelangganController {

    private $conn;

    public function __construct() {
        require_once 'config/Database.php';
        $db = new Database();
        $this->conn = $db->connect(); // PDO
    }

    public function getAll() {
        if (!$this->conn) {
            return [];
        }

        $stmt = $this->conn->query("SELECT * FROM pelanggan ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        // accept form-encoded POST or JSON body
        $inputJson = json_decode(file_get_contents('php://input'), true);
        if ($inputJson && empty($_POST)) {
            $_POST = $inputJson;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$this->conn) {
                $this->respondJson(['status' => 'error', 'message' => 'Database tidak tersedia.'], 500);
            }

            $validation = Validation::validatePelangganData($_POST);
            if (!$validation['valid']) {
                if ($this->isJsonRequest()) {
                    $this->respondJson(['status' => 'error', 'errors' => $validation['errors']], 422);
                } else {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['error'] = 'Validasi gagal. Periksa input.';
                    header('Location: index.php?action=pelanggan');
                }
                exit;
            }

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

            // ambil ID terakhir
            $id = $this->conn->lastInsertId();

            $this->respondJson([
                'status' => 'success',
                'data' => [
                    'id' => $id,
                    'no_internet' => $_POST['no_internet'],
                    'nama' => $_POST['nama'],
                    'no_tlp' => $_POST['no_tlp'],
                    'layanan' => $_POST['layanan'],
                    'harga' => $_POST['harga'],
                    'tagihan' => $_POST['tagihan'],
                    'status_pelanggan' => $_POST['status']
                ]
            ], 201);
        }
    }

    private function isJsonRequest()
    {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        return stripos($ct, 'application/json') !== false || strtolower($xhr) === 'xmlhttprequest';
    }

    private function respondJson($payload, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }

    

    public function delete() {
        if (!$this->conn) {
            exit;
        }

        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id=?");
        $stmt->execute([$_GET['id']]);
        exit;
    }

    public function searchAjax() {
        if (!$this->conn) {
            echo json_encode([]);
            exit;
        }

        $keyword = $_GET['keyword'] ?? '';

        $stmt = $this->conn->prepare("
            SELECT * FROM pelanggan 
            WHERE nama LIKE ? OR no_internet LIKE ?
        ");

        $search = "%$keyword%";
        $stmt->execute([$search, $search]);

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    public function login() {
        require "views/login.php";
    }

    public function loginProcess() {
        $user = new User();

        if ($user->getError()) {
            $_SESSION['error'] = "Koneksi database gagal: " . $user->getError();
            header("Location: index.php?action=login");
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $data = $user->login($username, $password);

        if ($data) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['success'] = "Login berhasil!";
            header("Location: index.php?action=dashboard");
            exit;
        } else {
            $_SESSION['error'] = "Username atau password salah.";
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function getStats() {
        if (!$this->conn) {
            return ['total' => 0, 'aktif' => 0, 'today' => 0];
        }

        $total = $this->conn->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
        $aktif = $this->conn->query("SELECT COUNT(*) FROM pelanggan WHERE status='aktif'")->fetchColumn();

        // Hitung pelanggan baru hari ini — coba beberapa nama kolom yang umum
        $today = 0;
        $possibleDateCols = ['created_at','created','tanggal','tgl','tgl_daftar','date_created'];
        foreach ($possibleDateCols as $col) {
            try {
                $stmt = $this->conn->query("SELECT COUNT(*) FROM pelanggan WHERE DATE($col) = CURDATE()");
                if ($stmt !== false) {
                    $today = (int) $stmt->fetchColumn();
                    break;
                }
            } catch (Exception $e) {
                // kolom tidak ada atau query gagal — coba kolom lain
                continue;
            }
        }

        return [
            'total' => $total,
            'aktif' => $aktif,
            'today' => $today
        ];
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    public function update()
    {
        if (!$this->conn) {
            header("Location: index.php?action=pelanggan");
            exit;
        }

        // support JSON body
        $inputJson = json_decode(file_get_contents('php://input'), true);
        if ($inputJson && empty($_POST)) {
            $_POST = $inputJson;
        }

        $validation = Validation::validatePelangganData($_POST);
        if (!$validation['valid']) {
            if ($this->isJsonRequest()) {
                $this->respondJson(['status' => 'error', 'errors' => $validation['errors']], 422);
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['error'] = 'Validasi gagal. Periksa input.';
                header('Location: index.php?action=pelanggan');
            }
            exit;
        }

        $stmt = $this->conn->prepare("\n            UPDATE pelanggan SET\n                no_internet=?,\n                nama=?,\n                no_tlp=?,\n                layanan=?,\n                harga=?,\n                tagihan=?,\n                status=?\n            WHERE id=?\n        ");

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

        if ($this->isJsonRequest()) {
            $this->respondJson(['status' => 'success']);
        }

        header("Location: index.php?action=pelanggan");
        exit;
    }

    public function exportCsv() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['login'])) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Forbidden';
            exit;
        }

        $data = $this->getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=pelanggan_export_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID','No Internet','Nama','No Tlp','Layanan','Harga','Tagihan','Status']);

        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'] ?? '',
                $row['no_internet'] ?? '',
                $row['nama'] ?? '',
                $row['no_tlp'] ?? '',
                $row['layanan'] ?? '',
                $row['harga'] ?? '',
                $row['tagihan'] ?? '',
                $row['status'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }
}
<?php

require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table = "pelanggan";
    private $error;

    public $id;
    public $nama;
    public $layanan;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->error = $database->getError();
    }

    public function getError() {
        return $this->error;
    }

    public function getAll() {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT * FROM pelanggan";
        return $this->conn->query($query);
    }

    public function create() {
        $query = "INSERT INTO pelanggan (nama, layanan) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->nama, $this->layanan]);
    }

    public function delete() {
        $query = "DELETE FROM pelanggan WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }

    public function getById() {
        $query = "SELECT * FROM pelanggan WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE pelanggan SET nama=?, layanan=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->nama, $this->layanan, $this->id]);
    }

    public function search($keyword) {
        $query = "SELECT * FROM pelanggan WHERE nama LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$keyword%"]);
        return $stmt;
    }

    public function login($username, $password) {
        if (!$this->conn) {
            return false;
        }

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function createUser($username, $password) {
        if (!$this->conn) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $query->execute([$username, $hash]);
    }

    public function getUserByUsername($username) {
        if (!$this->conn) {
            return false;
        }

        $query = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $query->execute([$username]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
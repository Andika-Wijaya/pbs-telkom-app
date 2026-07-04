<?php

require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table = "pelanggan";

    public $id;
    public $nama;
    public $layanan;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
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
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$username, $password]);
    return $stmt->fetch();
}

public function createUser($username, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $query = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    return $query->execute([$username, $hash]);
}

public function getUserByUsername($username) {
    $query = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([$username]);
    return $query->fetch(PDO::FETCH_ASSOC);
}
}
<?php
require_once __DIR__ . "/../config/Database.php";

class Service {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // CREATE
    public function tambah($nama, $status) {
        $query = "INSERT INTO services (nama, status) VALUES ('$nama','$status')";
        return $this->conn->query($query);
    }

    // READ
    public function getAll() {
        return $this->conn->query("SELECT * FROM services");
    }

    // DELETE
    public function delete($id) {
        return $this->conn->query("DELETE FROM services WHERE id=$id");
    }
}

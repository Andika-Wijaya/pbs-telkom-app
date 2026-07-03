<?php

class Database {
    private $host = "localhost";
    private $db_name = "telkom_db"; // ✅ SESUAIKAN DI SINI
    private $username = "root";
    private $password = "";

    public function connect() {
        $conn = null;

        try {
            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }

        return $conn;
    }
}
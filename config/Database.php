<?php

class Database {
    private $host = "127.0.0.1";
    private $db_name = "telkom_db";
    private $username = "root";
    private $password = "";
    private $error;

    public function connect() {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                $options
            );
            return $conn;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }

    public function getError() {
        return $this->error;
    }
}
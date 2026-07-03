<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "telkom_db";

    public function connect() {
        return new mysqli($this->host, $this->user, $this->pass, $this->db);
    }
}

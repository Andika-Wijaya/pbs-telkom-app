<?php
require_once "models/User.php";

class PelangganController {

    public function index() {
        require "views/index.php";
    }

    public function dashboard() {
        $user = new User();
        $data = $user->getAll();
        require "views/dashboard.php";
    }

    public function create() {
        $user = new User();
        $user->nama = $_POST['nama'];
        $user->layanan = $_POST['layanan'];
        $user->create();

        echo "success";
    }

    public function update() {
        $user = new User();
        $user->id = $_POST['id'];
        $user->nama = $_POST['nama'];
        $user->layanan = $_POST['layanan'];
        $user->update();

        echo "success";
    }
}
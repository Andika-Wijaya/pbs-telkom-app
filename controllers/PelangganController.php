<?php

require_once __DIR__ . '/../models/User.php';

class PelangganController {

    public function index() {
        include 'views/index.php';
    }

    public function dashboard() {
        $user = new User();
        $stmt = $user->getAll();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/dashboard.php';
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
        $user = new User();
        $keyword = $_GET['keyword'];
        $stmt = $user->search($keyword);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/dashboard.php';
    }
}
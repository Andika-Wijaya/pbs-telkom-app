<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'controllers/PelangganController.php';

$controller = new PelangganController();

$action = $_GET['action'] ?? 'dashboard';

switch($action){

    case 'dashboard':
        $stats = $controller->getStats();
        $total = $stats['total'];
        $aktif = $stats['aktif'];
        require 'views/dashboard_home.php';
    break;

    case 'pelanggan':
        $data = $controller->getAll();
        require 'views/dashboard.php';
    break;

    case 'create':
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $controller->create();
        } else {
            require 'views/index.php';
        }
    break;

    case 'update':
        $controller->update();
    break;

    case 'delete':
        $controller->delete();
    break;

    case 'searchAjax':
        $controller->searchAjax();
    break;

    case 'login':
        $controller->login();
    break;

    case 'loginProcess':
        $controller->loginProcess();
    break;

    case 'logout':
        $controller->logout();
    break;

    default:
        echo "404 Not Found";
    break;
}
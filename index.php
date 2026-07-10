<?php
session_start();

require_once 'controllers/PelangganController.php';

$controller = new PelangganController();

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {

    case 'dashboard':
        $stats = $controller->getStats();
        $total = $stats['total'];
        $aktif = $stats['aktif'];
        include 'views/dashboard_home.php';
        break;

    case 'pelanggan':
        $data = $controller->getAll();
        include 'views/dashboard.php';
        break;

    case 'create':
        $controller->create();
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
        header("Location: index.php?action=dashboard");
        break;
}
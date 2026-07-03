<?php

require_once 'controllers/PelangganController.php';

$controller = new PelangganController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'dashboard':
        $controller->dashboard();
        break;

    case 'create':
        $controller->create();
        break;

    case 'edit':
        $controller->edit();
        break;

    case 'delete':
        $controller->delete();
        break;

    default:
        $controller->index();
        break;
}
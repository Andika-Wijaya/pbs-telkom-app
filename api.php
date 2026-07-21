<?php
// Minimal JSON API to expose service-oriented endpoints for pelanggan.
require_once __DIR__ . '/controllers/PelangganController.php';

$controller = new PelangganController();

$action = $_GET['action'] ?? ($_SERVER['REQUEST_METHOD']);

// Simple routing
if ($action === 'listPelanggan' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'data' => $controller->getAll()]);
    exit;
}

if ($action === 'createPelanggan' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Let controller handle POST body (it supports JSON too)
    $controller->create();
}

if ($action === 'updatePelanggan') {
    // controller handles update
    $controller->update();
}

if ($action === 'deletePelanggan' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // expect id in query
    if (isset($_GET['id'])) {
        $controller->delete();
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'id is required']);
    }
}

// fallback
http_response_code(404);
echo json_encode(['success' => false, 'message' => 'Action not found']);

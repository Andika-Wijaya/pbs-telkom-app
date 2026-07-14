<?php
require_once __DIR__ . '/../config/Database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn instanceof PDO) {
    fwrite(STDERR, "Database connection failed\n");
    exit(1);
}

$result = $conn->query("SELECT 1 as test")->fetch(PDO::FETCH_ASSOC);
if (($result['test'] ?? null) != 1) {
    fwrite(STDERR, "Database query failed\n");
    exit(1);
}

echo "Database test passed\n";

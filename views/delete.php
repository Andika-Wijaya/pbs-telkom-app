<?php
require_once "models/Service.php";

$service = new Service();
$service->delete($_GET['id']);

header("Location: dashboard.php");
exit;

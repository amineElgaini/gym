<?php
require_once 'dashboard_db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode(getDashboardTotals());
    exit;
}

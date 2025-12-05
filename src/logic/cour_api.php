<?php
require_once 'cour_db.php';
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        // $id = intval($_GET['id']);
        // $result = getEquipementById($id);
        // echo json_encode($result);
        exit;
    } else {
        $result = getCours();
        echo json_encode($result);
        exit;
    }
}

if ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteData);
    $id = intval($deleteData['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        exit;
    }

    $result = deleteCour($id);
    echo json_encode($result);
    exit;
}


?>

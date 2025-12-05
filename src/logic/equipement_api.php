<?php
require_once 'equipement_db.php';
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $type_id = intval($_POST['type_id'] ?? 0);
    $status_id = intval($_POST['status_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);

    if (empty($name) || $type_id <= 0 || $status_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    $result = createEquipement($name, $type_id, $quantity, $status_id);
    echo json_encode($result);
    exit;
}

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = getEquipementById($id);
        echo json_encode($result);
        exit;
    } else {
        $result = getAllEquipements();
        echo json_encode($result);
        exit;
    }
}

if ($method === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    $id = intval($putData['id'] ?? 0);
    $name = trim($putData['name'] ?? '');
    $type_id = intval($putData['type_id'] ?? 0);
    $status_id = intval($putData['status_id'] ?? 0);
    $quantity = intval($putData['quantity'] ?? 0);

    if ($id <= 0 || empty($name) || $type_id <= 0 || $status_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid or missing fields"]);
        exit;
    }

    $result = updateEquipement($id, $name, $type_id, $quantity, $status_id);
    echo json_encode($result);
    exit;
}

if ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteData);
    $id = intval($deleteData['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        exit;
    }

    $result = deleteEquipement($id);
    echo json_encode($result);
    exit;
}

echo json_encode(["status" => "error", "message" => "Method not allowed"]);
exit;

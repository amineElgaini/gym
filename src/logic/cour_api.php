<?php
require_once 'cour_db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $max = intval($_POST['max'] ?? 0);

    if (empty($name) || $category_id <= 0 || $max <= 0) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    $result = createCours($name, $category_id, $max);
    echo json_encode($result);
    exit;
}

if ($method === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'category') {
        echo json_encode(getCoursCategories());
        exit;
    }

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        echo json_encode(getCoursById($id));
        exit;
    }

    echo json_encode(getCours());
    exit;
}

if ($method === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);

    $id = intval($putData['id'] ?? 0);
    $name = trim($putData['name'] ?? '');
    $category_id = intval($putData['category_id'] ?? 0);
    $max = intval($putData['max'] ?? 0);

    if ($id <= 0 || empty($name) || $category_id <= 0 || $max <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid or missing fields"]);
        exit;
    }

    echo json_encode(updateCours($id, $name, $category_id, $max));
    exit;
}

if ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteData);
    $id = intval($deleteData['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        exit;
    }

    echo json_encode(deleteCour($id));
    exit;
}

echo json_encode(["status" => "error", "message" => "Method not allowed"]);
exit;

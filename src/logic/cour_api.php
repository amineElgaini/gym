<?php
require_once 'cour_db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

// ======================= Existing Cours API =======================
if ($method === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_time') {
        // POST for adding a cours time
        $day = trim($_POST['day'] ?? '');
        $start_time = trim($_POST['start_time'] ?? '');
        $time_in_minutes = intval($_POST['time_in_minutes'] ?? 0);
        $cour_id = intval($_POST['cour_id'] ?? 0);

        if (empty($day) || empty($start_time) || $time_in_minutes <= 0 || $cour_id <= 0) {
            echo json_encode(["status" => "error", "message" => "Missing or invalid fields"]);
            exit;
        }

        echo json_encode(addCoursTime($day, $start_time, $time_in_minutes, $cour_id));
        exit;
    }

    // POST for creating a new course
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
    
    // DELETE for cours time
    if (isset($deleteData['action']) && $deleteData['action'] === 'delete_time') {
        // echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        $id = intval($deleteData['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid ID"]);
            exit;
        }
        echo json_encode(deleteCoursTime($id));
        exit;
    }

    // DELETE for course
    $id = intval($deleteData['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        exit;
    }

    echo json_encode(deleteCour($id));
    exit;
}
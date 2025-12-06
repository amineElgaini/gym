<?php
include __DIR__ . '/../config/db.php';


function addCoursTime($day, $start_time, $time_in_minutes, $cour_id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO cour_time (day, start_time, time_in_minutes, cour_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $day, $start_time, $time_in_minutes, $cour_id); // s = string, i = integer
        $stmt->execute();
        return ["status" => "success", "message" => "Cours time created successfully"];
    } catch (Exception $e) {
        error_log("AddCoursTime Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to create cours time"];
    }
}


function deleteCoursTime($id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM cour_time WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return ["status" => "success", "message" => "Cours time deleted successfully"];
    } catch (Exception $e) {
        error_log("CreateEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to delete cours time"];
    }
}

function createCours($name, $category_id, $max)
{
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO cours (name, category_id, max) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $name, $category_id, $max);
        $stmt->execute();
        return ["status" => "success", "message" => "Cours created successfully"];
    } catch (Exception $e) {
        error_log("CreateEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to create cours"];
    }
}

function updateCours($id, $name, $category_id, $max)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            UPDATE cours 
            SET name = ?, category_id = ?, max = ?
            WHERE id = ?
        ");
        $stmt->bind_param("siii", $name, $category_id, $max, $id);
        $stmt->execute();
        return ["status" => "success", "message" => "Cours updated successfully"];
    } catch (Exception $e) {
        error_log("UpdateCours Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to update cours"];
    }
}

// ===== Get all equipments =====
function getCours()
{
    global $conn;
    $sql = "SELECT 
            c.id,
            c.name,
            c.max,
            cc.name AS category,
            cc.id AS category_id,
            COUNT(DISTINCT ce.id) AS equipment_count,
            COUNT(DISTINCT ct.id) AS session_count
            FROM cours c
            INNER JOIN cour_category cc ON c.category_id = cc.id
            LEFT JOIN cour_equipment ce ON c.id = ce.cour_id
            LEFT JOIN cour_time ct ON c.id = ct.cour_id
            GROUP BY c.id
        ";

    $result = $conn->query($sql);
    $cours = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cours[] = $row;
        }
    }

    return ["status" => "success", "data" => $cours];
}

function getCoursCategories()
{
    global $conn;
    $sql = "SELECT id, name FROM cour_category";

    $result = $conn->query($sql);
    $cours = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cours[] = $row;
        }
    }

    return ["status" => "success", "data" => $cours];
}

function deleteCour($id)
{
    global $conn;
    try {

        $stmt = $conn->prepare("DELETE FROM cours WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return ["status" => "success", "message" => "Cour deleted successfully"];
    } catch (Exception $e) {
        error_log("DeleteCour Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to delete cour"];
    }
}

function getCoursById($id)
{

    try {
        global $conn;

        $sqlTime = "SELECT 
            c.id,
            c.name,
            c.max,
            cc.name AS category,
            cc.id AS category_id,
            COUNT(DISTINCT ce.id) AS equipment_count,
            COUNT(DISTINCT ct.id) AS session_count
            FROM cours c
            INNER JOIN cour_category cc ON c.category_id = cc.id
            LEFT JOIN cour_equipment ce ON c.id = ce.cour_id
            LEFT JOIN cour_time ct ON c.id = ct.cour_id
            WHERE c.id = ?
            GROUP BY c.id
        ";

        $stmtTime = $conn->prepare($sqlTime);
        $stmtTime->bind_param("i", $id);
        $stmtTime->execute();
        $coursResult = $stmtTime->get_result()->fetch_assoc();

        $sqlTime = "SELECT * FROM cour_time WHERE cour_id = ?";
        $stmtTime = $conn->prepare($sqlTime);
        $stmtTime->bind_param("i", $id);
        $stmtTime->execute();
        $timeResult = $stmtTime->get_result()->fetch_all(MYSQLI_ASSOC);

        $sqlEquip = "
            SELECT 
                ce.id,
                e.name AS name,
                es.name AS status,
                et.name AS type
            FROM cour_equipment ce
            INNER JOIN equipments e ON ce.equipment_id = e.id
            INNER JOIN equipment_status es ON e.status_id = es.id
            INNER JOIN equipment_types et ON e.type_id = et.id
            WHERE ce.cour_id = ?
        ";

        $stmtEquip = $conn->prepare($sqlEquip);
        $stmtEquip->bind_param("i", $id);
        $stmtEquip->execute();
        $equipResult = $stmtEquip->get_result()->fetch_all(MYSQLI_ASSOC);

        return [
            "status" => "success",
            "data" => [
                "cour" => $coursResult,
                "cour_time" => $timeResult,
                "cour_equipment" => $equipResult
            ]
        ];
    } catch (Exception $e) {
        error_log("GetCour Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to get course data"];
    }
}


function addEquipmentToCourse($cour_id, $equipment_id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            INSERT INTO cour_equipment (cour_id, equipment_id) 
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $cour_id, $equipment_id);
        $stmt->execute();

        return ["status" => "success", "message" => "Equipment added successfully"];
    } catch (Exception $e) {
        error_log("AddEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to add equipment"];
    }
}

function deleteEquipmentFromCourse($id)
{
    global $conn;
    try {
        // return ["status" => "success", "message" => $id];
        $stmt = $conn->prepare("DELETE FROM cour_equipment WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return ["status" => "success", "message" => "Equipment removed successfully"];
    } catch (Exception $e) {
        error_log("DeleteEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to delete equipment"];
    }
}

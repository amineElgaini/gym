<?php
include __DIR__ . '/../config/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable exceptions

// Get all equipments
function getAllEquipements()
{
    global $conn;
    try {
        $sql = "SELECT 
                e.id AS id,
                e.name AS name,
                et.name AS type,
                es.name AS status,
                e.type_id AS type_id,
                e.status_id AS status_id,
                e.quantity
            FROM equipments e
            INNER JOIN equipment_status es ON e.status_id = es.id
            INNER JOIN equipment_types et ON e.type_id = et.id
            ORDER BY e.id";

        $result = $conn->query($sql);
        $equipments = [];
        while ($row = $result->fetch_assoc()) {
            $equipments[] = $row;
        }
        return ["status" => "success", "data" => $equipments];

    } catch (Exception $e) {
        error_log("GetAllEquipements Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to fetch equipments"];
    }
}

// Get select options (types + statuses)
function getEquipementsTypeAndStatus()
{
    global $conn;
    try {
        $res = [];
        $result = $conn->query("SELECT id, name FROM equipment_types");
        $res['types'] = $result->fetch_all(MYSQLI_ASSOC);

        $result = $conn->query("SELECT id, name FROM equipment_status");
        $res['statuses'] = $result->fetch_all(MYSQLI_ASSOC);

        return ["status" => "success", "data" => $res];
    } catch (Exception $e) {
        error_log("GetEquipementsTypeAndStatus Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to fetch types and statuses"];
    }
}

// Get one equipment
function getEquipementById($id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM equipments WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $equip = $stmt->get_result()->fetch_assoc();
        if ($equip) {
            return ["status" => "success", "data" => $equip];
        }
        return ["status" => "error", "message" => "Equipment not found"];
    } catch (Exception $e) {
        error_log("GetEquipementById Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to fetch equipment"];
    }
}

// Create equipment
function createEquipement($name, $type_id, $quantity, $status_id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO equipments (name, type_id, quantity, status_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $name, $type_id, $quantity, $status_id);
        $stmt->execute();
        return ["status" => "success", "message" => "Equipment created successfully"];
    } catch (Exception $e) {
        error_log("CreateEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to create equipment"];
    }
}

// Update equipment
function updateEquipement($id, $name, $type_id, $quantity, $status_id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            UPDATE equipments 
            SET name = ?, type_id = ?, status_id = ?, quantity = ?
            WHERE id = ?
        ");
        $stmt->bind_param("siiii", $name, $type_id, $status_id, $quantity, $id);
        $stmt->execute();
        return ["status" => "success", "message" => "Equipment updated successfully"];
    } catch (Exception $e) {
        error_log("UpdateEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to update equipment"];
    }
}

// Delete equipment
function deleteEquipement($id)
{
    global $conn;
    try {
        // Check if this equipment is used in cour_equipment
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM cour_equipment WHERE equipment_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            return ["status" => "error", "message" => "This equipment is assigned to one or more courses."];
        }

        // Safe to delete
        $stmt = $conn->prepare("DELETE FROM equipments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return ["status" => "success", "message" => "Equipment deleted successfully"];
    } catch (Exception $e) {
        error_log("DeleteEquipment Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to delete equipment"];
    }
}

<?php
include __DIR__ . '/../config/db.php';


// ===== Get all equipments =====
function getCours() {
    global $conn;
    $sql = "SELECT 
    c.id,
    c.name,
    c.max,
    cc.name AS category,
    COUNT(ce.id) AS equipment_count,
    COUNT(ct.id) AS session_count
    FROM cours c
    INNER JOIN cour_category cc ON c.category_id = cc.id
    LEFT JOIN cour_equipment ce ON c.id = ce.cour_id
    LEFT JOIN cour_time ct ON c.id = ct.cour_id
    GROUP BY c.id, c.name, c.max, cc.name";

    $result = $conn->query($sql);
    $cours = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
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
?>

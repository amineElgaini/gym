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

    return $cours;
}

// ===== Get single equipment by ID =====
// function getEquipement($id) {
//     global $conn;
//     $stmt = $conn->prepare("SELECT * FROM equipements WHERE id=?");
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     return $result->fetch_assoc();
// }

// // ===== Add new equipment =====
// function addEquipement($nom, $type, $quantite, $etat) {
//     global $conn;
//     $stmt = $conn->prepare("INSERT INTO equipements (nom, type, quantite, etat) VALUES (?, ?, ?, ?)");
//     $stmt->bind_param("ssis", $nom, $type, $quantite, $etat);
//     return $stmt->execute();
// }

// // ===== Update equipment =====
// function updateEquipement($id, $nom, $type, $quantite, $etat) {
//     global $conn;
//     $stmt = $conn->prepare("UPDATE equipements SET nom=?, type=?, quantite=?, etat=? WHERE id=?");
//     $stmt->bind_param("ssisi", $nom, $type, $quantite, $etat, $id);
//     return $stmt->execute();
// }

// // ===== Delete equipment =====
// function deleteEquipement($id) {
//     global $conn;
//     $stmt = $conn->prepare("DELETE FROM equipements WHERE id=?");
//     $stmt->bind_param("i", $id);
//     return $stmt->execute();
// }
?>

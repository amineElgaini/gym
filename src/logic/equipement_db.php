<?php
include __DIR__ . '/../config/db.php';


// ===== Get all equipments =====
function getEquipements() {
    global $conn;
    $sql = "SELECT 
    e.id AS id,
    e.name AS name,
    et.name AS type,
    es.name AS status,
    e.quantity
    FROM equipments e
    INNER JOIN equipment_status es ON e.status_id = es.id
    INNER JOIN equipment_types et ON e.type_id = et.id";

    $result = $conn->query($sql);
    $equipements = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $equipements[] = $row;
        }
    }
    return $equipements;
}

// ===== Get single equipment by ID =====
function getEquipement($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// ===== Add new equipment =====
function addEquipement($nom, $type, $quantite, $etat) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO equipements (nom, type, quantite, etat) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nom, $type, $quantite, $etat);
    return $stmt->execute();
}

// ===== Update equipment =====
function updateEquipement($id, $nom, $type, $quantite, $etat) {
    global $conn;
    $stmt = $conn->prepare("UPDATE equipements SET nom=?, type=?, quantite=?, etat=? WHERE id=?");
    $stmt->bind_param("ssisi", $nom, $type, $quantite, $etat, $id);
    return $stmt->execute();
}

// ===== Delete equipment =====
function deleteEquipement($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM equipements WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>

<?php
include __DIR__ . '/../config/db.php';

function getDashboardTotals()
{
    global $conn;
    try {
        $totals = [];

        // Total users
        $result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
        $row = $result->fetch_assoc();
        $totals['total_users'] = $row['total_users'];

        // Total courses
        $result = $conn->query("SELECT COUNT(*) AS total_cours FROM cours");
        $row = $result->fetch_assoc();
        $totals['total_cours'] = $row['total_cours'];

        // Total equipment
        $result = $conn->query("SELECT COUNT(*) AS total_equipments FROM equipments");
        $row = $result->fetch_assoc();
        $totals['total_equipments'] = $row['total_equipments'];

        // Total sessions
        $result = $conn->query("SELECT COUNT(*) AS total_sessions FROM cour_time");
        $row = $result->fetch_assoc();
        $totals['total_sessions'] = $row['total_sessions'];

        return ["status" => "success", "data" => $totals];
    } catch (Exception $e) {
        error_log("DeleteCour Error: " . $e->getMessage());
        return ["status" => "error", "message" => "Failed to get dashboard status"];
    }
}

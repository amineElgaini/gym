<?php
$host = 'mysql';
$user = 'root';
$pass = 'root';
$dbname = 'gym_management';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Database connection error: ' . $conn->connect_error);
}
?>

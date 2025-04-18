<?php
// Afficher les erreurs PHP pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration for Raspberry Pi with MariaDB
$conn = new mysqli("localhost", "root", "", "tp_raspberry");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Créer la table si elle n'existe pas
$sql = "CREATE TABLE IF NOT EXISTS sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature FLOAT,
    humidity FLOAT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}
?>

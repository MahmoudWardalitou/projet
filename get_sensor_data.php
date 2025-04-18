<?php
header('Content-Type: application/json');

// Exécuter le script Python
$command = "python3 read_sensors.py";
$output = shell_exec($command);

if ($output === null) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to read sensor data"
    ]);
    exit;
}

// Décoder les données JSON
$data = json_decode($output, true);

if ($data === null) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid sensor data format"
    ]);
    exit;
}

// Retourner les données
echo json_encode($data);
?> 
<?php
include('db.php');
header('Content-Type: application/json');

$range = isset($_GET['range']) ? $_GET['range'] : '24h';

// Calculate the time range
switch($range) {
    case '7d':
        $timeLimit = "7 DAY";
        break;
    case '30d':
        $timeLimit = "30 DAY";
        break;
    default:
        $timeLimit = "24 HOUR";
}

// Get average temperature
$avgTemp = $conn->query("
    SELECT AVG(data) as avg_temp 
    FROM sensor_data 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL $timeLimit)"
)->fetch_assoc()['avg_temp'];

// Get total readings
$totalReadings = $conn->query("
    SELECT COUNT(*) as count 
    FROM sensor_data 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL $timeLimit)"
)->fetch_assoc()['count'];

// Get temperature trend data
$trendData = $conn->query("
    SELECT data, timestamp 
    FROM sensor_data 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL $timeLimit) 
    ORDER BY timestamp ASC"
);

$labels = [];
$values = [];
while ($row = $trendData->fetch_assoc()) {
    $labels[] = date('Y-m-d H:i', strtotime($row['timestamp']));
    $values[] = floatval($row['data']);
}

// Calculate temperature trend percentage
$firstTemp = count($values) > 0 ? $values[0] : 0;
$lastTemp = count($values) > 0 ? end($values) : 0;
$tempTrend = $firstTemp != 0 ? (($lastTemp - $firstTemp) / $firstTemp) * 100 : 0;

// Calculate temperature distribution
$distribution = $conn->query("
    SELECT 
        FLOOR(data) as temp_range,
        COUNT(*) as count
    FROM sensor_data 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL $timeLimit)
    GROUP BY FLOOR(data)
    ORDER BY temp_range"
);

$distLabels = [];
$distValues = [];
while ($row = $distribution->fetch_assoc()) {
    $distLabels[] = $row['temp_range'] . '°C';
    $distValues[] = intval($row['count']);
}

// Calculate metrics
$metrics = [
    [
        'name' => 'Average Temperature',
        'value' => number_format($avgTemp, 1) . '°C',
        'change' => $tempTrend,
        'status' => $avgTemp > 35 ? 'critical' : ($avgTemp > 30 ? 'warning' : 'good')
    ],
    [
        'name' => 'Data Points',
        'value' => $totalReadings,
        'change' => 0, // You could calculate this by comparing with previous period
        'status' => $totalReadings > 100 ? 'good' : 'warning'
    ],
    [
        'name' => 'System Status',
        'value' => 'Operational',
        'change' => 0,
        'status' => 'good'
    ]
];

// Prepare response
$response = [
    'avgTemp' => $avgTemp,
    'tempTrend' => $tempTrend,
    'totalReadings' => $totalReadings,
    'uptime' => rand(20, 100), // Simulated uptime
    'activeAlerts' => rand(0, 3), // Simulated alerts
    'totalSamples' => array_sum($distValues),
    'tempTrend' => [
        'labels' => $labels,
        'values' => $values
    ],
    'tempDist' => [
        'labels' => $distLabels,
        'values' => $distValues
    ],
    'metrics' => $metrics
];

echo json_encode($response);
?> 
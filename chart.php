<?php
include('db.php');
$res = $conn->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 10");
$labels = []; $values = [];
while ($row = $res->fetch_assoc()) {
  $labels[] = $row['timestamp'];
  $values[] = $row['data'];
}
echo json_encode(['labels' => array_reverse($labels), 'values' => array_reverse($values)]);
?>

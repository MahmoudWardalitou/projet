<?php
include('db.php');
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=temperature_data.csv');
$out = fopen('php://output', 'w');
fputcsv($out, array('ID', 'Température', 'Horodatage'));
$res = $conn->query("SELECT * FROM sensor_data");
while ($row = $res->fetch_assoc()) {
  fputcsv($out, $row);
}
fclose($out);
?>

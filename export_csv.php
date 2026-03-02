<?php
include 'db_connect.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=undi_export.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID Undi', 'No KP', 'Kategori', 'Lagu']);
$sql = "SELECT u.id_undi, u.nokp, k.kategori, l.nama_lagu FROM undian u JOIN kategori k ON u.id_kategori = k.id_kategori JOIN lagu l ON u.id_lagu = l.id_lagu ORDER BY u.id_undi ASC";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id_undi'], $row['nokp'], $row['kategori'], $row['nama_lagu']]);
}
fclose($output);
exit();
?>

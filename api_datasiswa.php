<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "connect.php";

$kelas = $_GET['kelas'] ?? '';

$stmt = $conn->prepare("
    SELECT * FROM siswaXI WHERE Kelas = ?
    UNION
    SELECT * FROM siswaX WHERE Kelas = ?
    ORDER BY Nama ASC
");
$stmt->bind_param("ss", $kelas, $kelas);
$stmt->execute();

$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>

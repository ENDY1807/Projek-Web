<?php
include "connect.php";

$kelas = $_GET["kelas"];

$data = [];

$q1 = $conn->query("SELECT * FROM siswa WHERE kelas='$kelas'");
while ($r = $q1->fetch_assoc()) {
    $data[] = [
        "nis" => $r["NIS"],
        "nama" => $r["Nama"]
    ];
}

$q2 = $conn->query("SELECT * FROM siswaXI WHERE kelas='$kelas'");
while ($r = $q2->fetch_assoc()) {
    $data[] = [
        "nis" => $r["NIS"],
        "nama" => $r["Nama"]
    ];
}

echo json_encode($data);

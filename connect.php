<?php
$conn = new mysqli("localhost", "root", "", "absensi_kelas");

if ($conn->connect_error) {
    die("Gagal konek DB: " . $conn->connect_error);
}
?>

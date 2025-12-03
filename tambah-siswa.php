<?php
include "connect.php";

if (!isset($_POST["nama"])) {
    echo "error";
    exit;
}

$nama = mysqli_real_escape_string($conn, $_POST["nama"]);

$sql = "INSERT INTO siswa (nama) VALUES ('$nama')";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    echo "error";
}
?>

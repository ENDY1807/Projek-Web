<?php
include "connect.php";

if (!isset($_POST["id"])) {
    echo "error";
    exit;
}

$id = intval($_POST["id"]);

$sql = "DELETE FROM siswa WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    echo "error";
}
?>

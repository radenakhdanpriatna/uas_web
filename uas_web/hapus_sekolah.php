<?php
include 'config.php';
$id = $_GET['id'];
$sql = "DELETE FROM sekolah WHERE id = '$id'";
echo ($conn->query($sql)) ? "OK" : "Gagal";

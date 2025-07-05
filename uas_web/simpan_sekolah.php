<?php
include 'config.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$lat = $_POST['latitude'];
$lng = $_POST['longitude'];

if ($id == "") {
    $sql = "INSERT INTO sekolah (nama, alamat, latitude, longitude) VALUES ('$nama', '$alamat', '$lat', '$lng')";
} else {
    $sql = "UPDATE sekolah SET nama='$nama', alamat='$alamat', latitude='$lat', longitude='$lng' WHERE id='$id'";
}

if ($conn->query($sql)) {
    echo "OK";
} else {
    echo "Gagal";
}

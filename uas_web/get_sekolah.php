<?php
include 'config.php';
$data = [];
$q = $conn->query("SELECT * FROM sekolah");
while ($r = $q->fetch_assoc()) {
    $data[] = $r;
}
echo json_encode($data);

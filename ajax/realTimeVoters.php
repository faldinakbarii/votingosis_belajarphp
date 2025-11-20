<?php 
require '../include/functions.php';

$results = ShowsUseObj("SELECT nama, jumlah_suara, kelas FROM candidate ORDER BY id ASC");

echo json_encode($results);
exit;
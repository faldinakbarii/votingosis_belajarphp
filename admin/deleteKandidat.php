<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';

$id = $_GET["id"];

if (!isset($id)) {
    header("Location: tambahKandidat.php");
    exit;
}

$database->query("DELETE FROM candidate WHERE id = '$id'");

if ($database->affected_rows > 0) {
    echo "
        <script>
        alert('Kandidat berhasil dihapus!')
        document.location.href = 'tambahKandidat.php'
        </script>
        ";
} else {
    echo "
        <script>
        alert('Kandidat gagal dihapus!')
        document.location.href = 'tambahKandidat.php'
        </script>
        ";
}
exit;
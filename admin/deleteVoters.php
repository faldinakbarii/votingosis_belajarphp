<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';

$id = $_GET["id"];

if (!isset($id)) {
    header("Location: tambahVoters.php");
    exit;
}

$database->query("DELETE FROM voters WHERE id = '$id'");

if ($database->affected_rows > 0) {
    echo "
        <script>
        alert('Voter berhasil dihapus!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
} else {
    echo "
        <script>
        alert('Voter gagal dihapus!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
}
exit;
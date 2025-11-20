<?php 
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';

$database->query("DELETE FROM voters");

if ($database->affected_rows > 0) {
    echo "
        <script>
        alert('Semua Voter berhasil dihapus!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
} else {
    echo "
        <script>
        alert('Semua Voter gagal dihapus!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
}

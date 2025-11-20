<?php 
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}

require '../include/functions.php';

$database->query("DELETE FROM candidate");
$database->query("DELETE FROM voters");
$database->query("DELETE FROM admin WHERE role = 'admin'");

if ($database->affected_rows > 0) {
    echo "
        <script>
        alert('Semua sistem berhasil di RESET!')
        document.location.href = 'admin.php'
        </script>
        ";
} else {
    echo "
        <script>
        alert('Semua sistem berhasil di RESET!')
        document.location.href = 'admin.php'
        </script>
        ";
}
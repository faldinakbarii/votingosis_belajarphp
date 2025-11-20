<?php 
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}

require '../include/functions.php';

$database->query("UPDATE candidate SET jumlah_suara = 0");

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
<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';
require '../vendor/autoload.php';

$id = $_GET["id"];

if (!isset($id)) {
    header("Location: tambahVoter.php");
    exit;
}

if (isset($_POST["edit"])) {
    $tambahVoter = editVoters($_POST);
    if ($tambahVoter > 0) {
        echo "
        <script>
        alert('Voter berhasil diedit!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
    } else {
        echo "
        <script>
        alert('Voter gagal diedit!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
    }
}

$q = "SELECT * FROM voters WHERE id = '$id'";
$semua = ShowsUseObj($q)[0];



?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta Voters - OSIS 2025</title>
    <link rel="stylesheet" href="../assets/css/editVoters.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1>👥 Kelola Peserta Voters</h1>
                <p>Edit Pemilih OSIS 2025</p>
            </div>
            <div class="header-buttons">
                <a href="admin.php" class="btn-back"">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </header>

        <div class=" main-content">
                    <div class="form-section">
                        <h2>✏ Edit Voter</h2>

                        <form id="voterForm" method="post" action="">
                            <input type="hidden" name="id" value="<?= $semua->id ?>">
                            <div class="form-group">
                                <label for="nisn">NISN <span class="required">*</span></label>
                                <input type="text" id="nisn" class="form-control" placeholder="Masukkan NISN"
                                    name="nisn" value="<?= $semua->nisn ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="namaDepan">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" id="namaDepan" class="form-control" placeholder="Nama lengkap"
                                    name="nama" value="<?= $semua->nama ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="kelas">Kelas <span class="required">*</span></label>
                                <input type="text" id="kelas" class="form-control"
                                    placeholder="Masukkan kelas sekaligus jurusan Contoh: X-TKJ 1" name="kelas"
                                    value="<?= $semua->kelas ?>"
                                    required>
                            </div>

                            <button type="submit" class="submit-btn" name="edit">
                                Edit Voter
                            </button>

                            <button type="button" class="reset-btn" onclick="resetForm()">
                                Reset Form
                            </button>

                        </form>

                    </div>
            </div>
    </div>

    <script src="../assets/js/tambahVoter.js">
    </script>
</body>

</html>
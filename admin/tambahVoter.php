<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';
require '../vendor/autoload.php';
$q = "SELECT COUNT(*) AS jumlahdata FROM voters";
$result = $database->query($q);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if (isset($_POST["tambahBulk"])) {
    if ($tambahBulk = tambahBulk($_FILES) > 0) {
        echo "
        <script>
        alert('Voter berhasil ditambahkan!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
        exit;
    } else {
        echo "
        <script>
        alert('Voter gagal ditambahkan!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
        exit;
    }
}


if (isset($_POST["tambah"])) {
    $tambahVoter = tambahVoter($_POST);
    if ($tambahVoter > 0) {
        echo "
        <script>
        alert('Voter berhasil ditambahkan!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
        exit;
    } else {
        echo "
        <script>
        alert('Voter gagal ditambahkan!')
        document.location.href = 'tambahVoter.php'
        </script>
        ";
        exit;
    }
}

$jumlahDataSatuHalaman = 7;
$jumlahData = $result->fetch_object()->jumlahdata;
$jumlahHalaman = ceil($jumlahData / $jumlahDataSatuHalaman);
$halamanAktif = isset($_GET["p"]) ? $_GET["p"] : 1;
$index = ($jumlahDataSatuHalaman * $halamanAktif) - $jumlahDataSatuHalaman;

$pagination = ShowsUseObj("SELECT * FROM voters LIMIT $index, $jumlahDataSatuHalaman");
$isVoted = ShowsUseObj("SELECT COUNT(*) AS sudahvote FROM voters WHERE status_vote = '1'")[0];
$username = $_SESSION["dataUser"] -> username;
$role = ShowsUseObj("SELECT role FROM admin WHERE username = '$username'")[0];








?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta Voters - OSIS 2025</title>
    <link rel="stylesheet" href="../assets/css/tambahVoter.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1>👥 Kelola Peserta Voters</h1>
                <p>Tambah dan Kelola Data Pemilih OSIS 2025</p>
            </div>
            <div class="header-buttons">
                <a href="admin.php" class="btn-back"">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </header>

        <div class=" main-content">
                    <!-- Form Tambah Voter -->
                    <div class="form-section">
                        <h2>➕ Tambah Voter Baru</h2>

                        <form id="voterForm" method="post" action="">
                            <div class="form-group">
                                <label for="nisn">NISN <span class="required">*</span></label>
                                <input type="text" id="nisn" class="form-control" placeholder="Masukkan NISN"
                                    name="nisn" required>
                            </div>

                            <div class="form-group">
                                <label for="namaDepan">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" id="namaDepan" class="form-control" placeholder="Nama lengkap"
                                    name="nama" required>
                            </div>

                            <div class="form-group">
                                <label for="kelas">Kelas <span class="required">*</span></label>
                                <input type="text" id="kelas" class="form-control"
                                    placeholder="Masukkan kelas sekaligus jurusan Contoh: X-TKJ 1" name="kelas"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="nomorKelas">Password <span class="required">*</span> <span style="font-style: italic; font-size: 10px; color: grey;">Password tidak dapat diubah!</span></label>
                                <input type="text" id="password" class="form-control" name="password"
                                    placeholder="Masukkan password untuk voter" autocomplete="off" required>
                            </div>

                            <div class="form-group">
                                <label for="nomorKelas">Konfirmasi Password <span class="required">*</span></label>
                                <input type="text" id="password" class="form-control" name="passwordC"
                                    placeholder="Masukkan konfirmasi password untuk voter" autocomplete="off" required>
                            </div>

                            <button type="submit" class="submit-btn" name="tambah">
                                Tambah Voter
                            </button>

                            <button type="button" class="reset-btn" onclick="resetForm()">
                                Reset Form
                            </button>

                        </form>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="bulk-import">
                                <p>📊 Import data voter dalam jumlah banyak?</p>
                                <p>Upload file excel!!</p>
                                <input type="file" name="bulk" id="bulk">
                                <button type="submit" name="tambahBulk">Tambah Sekarang!</button>
                            </div>
                        </form>


                    </div>

                    <div class="voters-list-section">
                        <h2>
                            📋 Daftar Voters
                            <span style="font-size: 1rem; color: #666;">(Jumlah Voters: <strong
                                    id="totalVoters"><?= $jumlahData ?></strong>)</span>
                        </h2>


                        <div class="search-box">
                            <input type="text" id="keyword" placeholder="🔍 Cari berdasarkan nama, NISN, atau kelas...">
                            <button>Cari</button>
                        </div>

                        <div class="stats-bar">
                            <p>Voter yang sudah memilih: <strong id="votedCount"><?= $isVoted->sudahvote ?></strong> /
                                <strong id="totalCount"><?= $jumlahData ?></strong>
                            </p>
                            <br>
                            <?php if ($role->role == "superadmin" || isset($_SESSION["superadmin"])) :  ?>
                            <a href="deleteAll.php" onclick="return twiceConfirm();"><button class="btn-delete">🗑️ Hapus Semua Data</button></a>
                            <?php endif; ?>
                        </div>

                        <div class="pagination">
                            <?php if ($halamanAktif > 1): ?>
                                <div class="backward">
                                    <a href="?p=<?= $halamanAktif - 1 ?>">
                                        <<< </a>
                                </div>
                            <?php endif; ?>

                            <div class="center">
                                <p>-1----Halaman----+1</p>
                            </div>

                            <?php if ($halamanAktif < $jumlahHalaman): ?>
                                <div class="forward">
                                    <a href="?p=<?= $halamanAktif + 1 ?>">>>></a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="votersList">
                            <?php foreach ($pagination as $voters): ?>
                                <div class="voter-card">
                                    <div class="voter-info">
                                        <h4><?= $voters->nama ?></h4>
                                        <p>NISN: <?= $voters->nisn ?> | Kelas: <?= $voters->kelas ?></p>
                                    </div>
                                    <div class="voter-actions">
                                        <a href="editVoters.php?id=<?= $voters->id ?>"><button class="btn-edit"">✏️ Edit</button></a>
                                        <a href=" deleteVoters.php?id=<?= $voters->id ?>"
                                            onclick="confirm('Apakah anda yakin ingin menghapus data voter ini?')"><button
                                                    class="btn-delete">🗑️ Hapus</button></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
            </div>
    </div>

    <script src="../assets/js/tambahVoter.js">
    </script>
</body>

</html>
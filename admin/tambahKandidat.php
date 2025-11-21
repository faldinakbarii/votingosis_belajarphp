<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';
require '../vendor/autoload.php';
$q = "SELECT COUNT(*) AS jumlahdata FROM candidate";
$result = $database->query($q);

if (isset($_POST["tambah"])) {
    $tambahVoter = tambahKandidat($_POST);
    if ($tambahVoter > 0) {
        echo "
        <script>
        alert('Kandidat berhasil ditambahkan!')
        document.location.href = 'tambahKandidat.php'
        </script>
        ";
        exit;
    } else {
        echo "
        <script>
        alert('Kandidat gagal ditambahkan!')
        document.location.href = 'tambahKandidat.php'
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

$pagination = ShowsUseObj("SELECT * FROM candidate LIMIT $index, $jumlahDataSatuHalaman");





?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta Voters - OSIS 2025</title>
    <link rel="stylesheet" href="../assets/css/tambahVoter.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
</head>

<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1>👥 Kelola Kandidat Calon Ketua OSIS</h1>
                <p>Tambah dan Kelola Data Kandidat Calon Ketua OSIS 2025</p>
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
                        <h2>➕ Tambah Kandidat Baru</h2>

                        <form id="voterForm" method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nisn">NISN Ketua<span class="required">*</span></label>
                                <input type="text" id="nisn" class="form-control" placeholder="Masukkan NISN ketua"
                                    name="nisnK" required>
                            </div>

                            <div class="form-group">
                                <label for="nisn">NISN Wakil<span class="required">*</span></label>
                                <input type="text" id="nisn" class="form-control" placeholder="Masukkan NISN wakil"
                                    name="nisnW" required>
                            </div>

                            <div class="form-group">
                                <label for="namaDepan">Nama Lengkap Ketua<span class="required">*</span></label>
                                <input type="text" id="namaDepan" class="form-control" placeholder="Nama lengkap ketua"
                                    name="namaK" required>
                            </div>

                            <div class="form-group">
                                <label for="namaDepan">Nama Lengkap Wakil<span class="required">*</span></label>
                                <input type="text" id="namaDepan" class="form-control" placeholder="Nama lengkap wakil"
                                    name="namaW" required>
                            </div>


                            <div class="form-group">
                                <label for="kelas">Kelas Ketua<span class="required">*</span></label>
                                <input type="text" id="kelas" class="form-control"
                                    placeholder="Masukkan kelas sekaligus jurusan Contoh: X-TKJ 1" name="kelasK"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="kelas">Kelas Wakil<span class="required">*</span></label>
                                <input type="text" id="kelas" class="form-control"
                                    placeholder="Masukkan kelas sekaligus jurusan Contoh: X-TKJ 1" name="kelasW"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="visi">Visi<span class="required">*</span></label>
                                <textarea name="visi" id="visi"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="misi">Misi<span class="required">*</span></label>
                                <textarea name="misi" id="misi"></textarea>
                            </div>

                            <div class="bulk-import">
                                <p>Upload foto pasangan kandidat calon ketua OSIS</p>
                                <input type="file" name="foto" id="bulk" required>
                            </div>

                            <button type="submit" class="submit-btn" name="tambah">
                                Tambah Kandidat
                            </button>

                            <button type="button" class="reset-btn" onclick="resetForm()">
                                Reset Form
                            </button>

                        </form>

                    </div>

                    <div class="voters-list-section">
                        <h2>
                            📋 Daftar Kandidat
                            <span style="font-size: 1rem; color: #666;">(Jumlah Kandidat: <strong
                                    id="totalVoters"><?= $jumlahData ?></strong>)</span>
                        </h2>


                        <div class="search-box">
                            <input type="text" id="keyword" placeholder="🔍 Cari berdasarkan nama, NISN, atau kelas...">
                            <button>Cari</button>
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
                                        <p>Kelas: <?= $voters->kelas ?></p>
                                    </div>
                                    <div class="voter-actions">
                                        <a href="editKandidat.php?id=<?= $voters->id ?>"><button class="btn-edit">✏️ Edit</button></a>
                                        <a href=" deleteKandidat.php?id=<?= $voters->id ?>"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data kandidat ini?')"><button
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
    <script>
    ClassicEditor
        .create( document.querySelector( '#visi' ) )
        .catch( error => { console.error( error ); } );

    ClassicEditor
        .create( document.querySelector( '#misi' ) )
        .catch( error => { console.error( error ); } );
</script>
</body>

</html>
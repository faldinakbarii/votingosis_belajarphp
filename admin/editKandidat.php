<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}
require '../include/functions.php';
require '../vendor/autoload.php';

if (isset($_POST["edit"])) {
    $tambahVoter = editKandidat($_POST);
    if ($tambahVoter > 0) {
        echo "
        <script>
        alert('Kandidat berhasil diedit!')
        document.location.href = 'editKandidat.php'
        </script>
        ";
        exit;
    } else {
        echo "
        <script>
        alert('Kandidat gagal diedit!')
        document.location.href = 'editKandidat.php'
        </script>
        ";
        exit;
    }
}

$id = $_GET["id"];

if (!isset($id)) {
    header("Location: tambahKandidat.php");
    exit;
}


$hasil = ShowsUseObj("SELECT * FROM candidate WHERE id = '$id'")[0];



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
                <h1>👥 Edit Kandidat Calon Ketua OSIS</h1>
            </div>
            <div class="header-buttons">
                <a href="tambahKandidat.php" class="btn-back">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </header>

        <div class=" main-content">
                    <!-- Form Tambah Voter -->
                    <div class="form-section">
                        <h2>➕ Edit Kandidat</h2>

                        <form id="voterForm" method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $hasil->id ?>">
                            <input type="hidden" name="fotoLama" value="<?= $hasil->foto ?>">
                            <div class="form-group">
                                <label for="nisn">NISN Ketua<span class="required">*</span></label>
                                <input type="text" id="nisn" class="form-control" placeholder="Masukkan NISN ketua"
                                    name="nisnK" value="<?= $hasil->nisn_ketua ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="nisn">NISN Wakil<span class="required">*</span></label>
                                <input type="text" id="nisn" class="form-control" placeholder="Masukkan NISN wakil"
                                    name="nisnW" value="<?= $hasil->nisn_wakil ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="namaDepan">Nama Lengkap Ketua<span class="required">*</span></label>
                                <input type="text" id="namaDepan" class="form-control" placeholder="Nama lengkap ketua"
                                    name="namaK" value="<?= explode('&', $hasil->nama)[0]; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="namaDepan">Nama Lengkap Wakil<span class="required">*</span></label>
                                <input type="text" id="namaDepan" class="form-control" placeholder="Nama lengkap wakil"
                                    name="namaW" value="<?= explode('&', $hasil->nama)[1]; ?>" required>
                            </div>


                            <div class="form-group">
                                <label for="kelas">Kelas Ketua<span class="required">*</span></label>
                                <input type="text" id="kelas" class="form-control" value="<?= explode('&', $hasil->kelas)[0]; ?>"
                                    placeholder="Masukkan kelas sekaligus jurusan Contoh: X-TKJ 1" name="kelasK"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="kelas">Kelas Wakil<span class="required">*</span></label>
                                <input type="text" id="kelas" class="form-control" value="<?= explode('&', $hasil->kelas)[1]; ?>"
                                    placeholder="Masukkan kelas sekaligus jurusan Contoh: X-TKJ 1" name="kelasW"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="visi">Visi<span class="required">*</span></label>
                                <textarea name="visi" id="visi"><?= $hasil->visi ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="misi">Misi<span class="required">*</span></label>
                                <textarea name="misi" id="misi"><?= $hasil->misi ?></textarea>
                            </div>

                            <div class="bulk-import">
                                <img src="../assets/img/<?= $hasil->foto ?>" alt="fotoTersedia" width="200">
                                <p>Upload foto pasangan kandidat calon ketua OSIS</p>
                                <input type="file" name="foto" id="bulk">
                            </div>

                            <button type="submit" class="submit-btn" name="edit">
                                Edit Kandidat
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
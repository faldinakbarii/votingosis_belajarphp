<?php 
session_start();
require  'include/functions.php';

if (!isset($_SESSION["loginUser"])) {
    header("Location: auth/login.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');

$tanggalSekarang = date('Y-m-d');
$tanggalWaktuSekarang = date('Y-m-d H:i:s');

$hasil = ShowsUseObj("SELECT * FROM candidate");


$nisn = $_SESSION["dataUser"] -> nisn;
$nama = $_SESSION["dataUser"] -> nama;
$statusVote = ShowsUseObj("SELECT status_vote FROM voters WHERE nisn = '$nisn'")[0];
$memilih = ShowsUseObj("SELECT vote_who FROM voters WHERE nisn = '$nisn'")[0];


if (isset($_POST["vote"])) {
    if ($statusVote->status_vote == 1) {
        echo "
        <script>
        alert('Anda sudah melakukan vote!')
        document.location.href = 'index.php'
        </script>
        ";

        return;
    }

    $id = $_POST["id"];
    $voteWho = ShowsUseObj("SELECT * FROM candidate WHERE id = '$id'")[0]->nama;

    $database->query("UPDATE candidate SET jumlah_suara = jumlah_suara + 1 WHERE id = '$id'");
    $database->query("UPDATE voters SET status_vote = '1', vote_time = '$tanggalWaktuSekarang', vote_who = '$voteWho' WHERE nisn = '$nisn'");

    if ($database->affected_rows > 0) {
        echo "
        <script>
        alert('Voting berhasil!')
        document.location.href = 'index.php'
        </script>
        ";
    } 
}


?>


<?php if ($statusVote->status_vote == 0) : ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Ketua OSIS 2025</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🗳️ Pemilihan Ketua OSIS 2025</h1>
            <p>Pilih Pemimpin Terbaik untuk OSIS Kita!</p>
            <p>Halo, <?= $nama . ' | ' . $nisn ?></p>
            <p>Anda memilih: Belum memilih</p>
            <a href="auth/logout.php"><button class="logout-btn">Logout</button></a>
        </header>

        <div class="candidates-grid">
            <?php foreach ($hasil as $kandidat) : ?>
            <!-- Kandidat 1 -->
            <div class="candidate-card">
                <div class="candidate-photo">
                    <img src="assets/img/<?= $kandidat->foto ?>" style="width: 80%;">
                </div>
                <form class="candidate-info" action="" method="POST">
                <input type="hidden" name="id" value="<?= $kandidat->id ?>">
                    <h2 class="candidate-name"><?= $kandidat->nama ?></h2>
                    <p class="candidate-class">Kelas <?= $kandidat->kelas ?></p>
                    
                    <div class="visi-misi-section">
                        <h3>VISI</h3>
                        <p><?= $kandidat->visi ?></p>
                        
                        <h3 style="margin-top: 15px;">MISI</h3>
                        <?= $kandidat->misi ?>
                    </div>
                    
                    <button class="vote-btn" name="vote">Vote Kandidat 1 ( <?= $kandidat->jumlah_suara?> Suara)</button>
            </form>
            </div>
            <?php endforeach; ?>
        </div>

        <footer>
            <p>&copy; 2025 Pemilihan Ketua OSIS | Satu Suara, Satu Perubahan</p>
        </footer>
    </div>

    <script>
    </script>
</body>
</html>

<?php else : ?>
    <?php global $voteWho; ?>

    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Ketua OSIS 2025</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🗳️ Pemilihan Ketua OSIS 2025</h1>
            <p>Pilih Pemimpin Terbaik untuk OSIS Kita!</p>
            <p>Halo, <?= $nama . ' | ' . $nisn ?></p>
            <p>Anda memilih: <?= $memilih -> vote_who ?></p>
            <a href="auth/logout.php"><button class="logout-btn">Logout</button></a>
        </header>

        <div class="candidates-grid">
            <?php foreach ($hasil as $kandidat) : ?>
            <!-- Kandidat 1 -->
            <div class="candidate-card">
                <div class="candidate-photo">
                <img src="assets/img/<?= $kandidat->foto ?>" style="width: 80%;">
                </div>
                <div class="candidate-info">
                    <h2 class="candidate-name"><?= $kandidat->nama ?></h2>
                    <p class="candidate-class">Kelas <?= $kandidat->kelas ?></p>
                    
                    <div class="visi-misi-section">
                        <h3>VISI</h3>
                        <p><?= $kandidat->visi ?></p>
                        
                        <h3 style="margin-top: 15px;">MISI</h3>
                        <?= $kandidat->misi ?>
                    </div>
                    

                    <p class="has_voted">Anda sudah memberikan suara. (Total suara: <?= $kandidat->jumlah_suara ?>)</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <footer>
            <p>&copy; 2025 Pemilihan Ketua OSIS | Satu Suara, Satu Perubahan</p>
        </footer>
    </div>

    <script>
    </script>
</body>
</html>

<?php endif; ?>
<?php
require __DIR__ . '/../include/functions.php';

$keyword = $_GET["keyword"];

$q = "SELECT COUNT(*) AS jumlahdata FROM voters";
$result = $database->query($q);

$jumlahDataSatuHalaman = 7;
$jumlahData = $result->fetch_object()->jumlahdata;
$jumlahHalaman = ceil($jumlahData / $jumlahDataSatuHalaman);
$halamanAktif = isset($_GET["p"]) ? $_GET["p"] : 1;
$index = ($halamanAktif * $jumlahDataSatuHalaman) - $jumlahDataSatuHalaman;

$nourut = 1;

if (trim($keyword) === '') {
    $semua = ShowsUseObj("SELECT * FROM voters LIMIT $index, $jumlahDataSatuHalaman");
} else {
    $query = "SELECT * FROM voters WHERE
            nama LIKE '%$keyword%' OR
            nisn LIKE '%$keyword%' OR
            kelas LIKE '%$keyword%'";

    $semua = ShowsUseObj($query);
}

?>

<div id="votersList">
    <?php foreach ($semua as $voters): ?>
        <div class="voter-card">
            <div class="voter-info">
                <h4><?= $voters->nama ?></h4>
                <p>NISN: <?= $voters->nisn ?> | Kelas: <?= $voters->kelas ?></p>
            </div>
            <div class="voter-actions">
                <button class="btn-edit" onclick="editVoter('Ahmad Rizki')">✏️ Edit</button>
                <button class="btn-delete" onclick="deleteVoter('Ahmad Rizki')">🗑️ Hapus</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
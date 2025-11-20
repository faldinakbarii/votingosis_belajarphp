<?php 
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}

require '../include/functions.php';

$username = $_SESSION["dataUser"] -> username;
$q = "SELECT COUNT(*) AS jumlahdata FROM voters";
$result = $database->query($q);
$jumlahData = $result->fetch_object()->jumlahdata;
$isVoted = ShowsUseObj("SELECT COUNT(*) AS sudahvote FROM voters WHERE status_vote = '1'")[0];
$daftarPemilihTerakhir = ShowsUseObj("SELECT * FROM voters ORDER BY vote_time DESC LIMIT 5");
$role = ShowsUseObj("SELECT role FROM admin WHERE username = '$username'")[0];


?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vote OSIS 2025</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1>🎯 Admin Dashboard</h1>
                <p>Pemilihan Ketua OSIS 2025</p>
                <strong>Welcome, <?= $username ?>!</strong>
                <p>Role: <strong><?= ucfirst($role->role) ?></strong></p>
            </div>
            <a href="auth/logout.php"><button class="logout-btn">Logout</button></a>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number" id="totalVotes"><?= $isVoted->sudahvote ?></div>
                <div class="stat-label">Total Suara</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number" id="totalVoters"><?= $jumlahData ?></div>
                <div class="stat-label">Peserta</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👨🏻‍💻</div>
                <div class="stat-number" id="timeRemaining"><?= count(ShowsUseObj("SELECT * FROM admin")) ?></div>
                <div class="stat-label">Jumlah Admin</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number" id="timeRemaining"><?= count(ShowsUseObj("SELECT * FROM candidate")) ?></div>
                <div class="stat-label">Jumlah Kandidat</div>
            </div>
        </div>

        <div class="main-content">
            <div class="results-section">
                <h2>📊 Hasil Real-Time</h2>
                <div id="candidateContainer"></div>
            </div>

            <div class="actions-section">
                <h2>⚙️ Aksi Admin</h2>
                <div class="action-buttons">
                    <button class="action-btn btn-primary" onclick="location.reload()">
                        🔄 Refresh Data
                    </button>
                    <?php if ($role->role == "superadmin" || isset($_SESSION["superadmin"])) :  ?>
                    <a href="exportVoters.php" style="text-decoration: none;"><button class="action-btn">
                        📥 Export Excel (Voters)
                    </button> </a>
                    <a href="exportKandidat.php" style="text-decoration: none;"><button class="action-btn">
                        📥 Export Excel (Kandidat)
                    </button> </a>
                    <?php endif; ?>
                    <button class="action-btn btn-secondary" onclick="printResults()">
                        🖨️ Print Hasil
                    </button>
                    <a href="tambahKandidat.php" style="text-decoration: none;"><button class="action-btn">
                         ➕ Tambah Kandidat
                    </button> </a>
                    <a href="tambahVoter.php" style="text-decoration: none;"><button class="action-btn btn-danger">
                         👥 Tambah Peserta
                    </button> </a>
                    <?php if ($role->role == "superadmin" || isset($_SESSION["superadmin"])) :  ?>
                    <a href="reset.php" onclick="return twiceConfirm()" style="text-decoration: none;"><button class="action-btn btn-danger">
                        🗑️ Reset Sistem
                    </button> </a>
                    <a href="auth/register.php"  style="text-decoration: none;"><button class="action-btn">
                    👨‍🏫 Tambahkan Admin
                    </button> </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="voters-list">
            <h2>📋 Daftar Pemilih Terakhir</h2>
            <div class="voter-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Pilihan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="voterTableBody">
                        <?php $i = 1; ?>
                        <?php foreach ($daftarPemilihTerakhir as $daftar) : ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $daftar -> nama ?></td>
                            <td><?= $daftar-> kelas ?></td>
                            <td><?= $daftar->vote_who ?></td>
                            <td><?= $daftar->vote_time ?></td>
                        </tr>
                        <?php $i++ ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js">

    </script>
</body>
</html>
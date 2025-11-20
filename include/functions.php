<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


$tanggalSekarang = date('Y-m-d');
$tanggalWaktuSekarang = date('Y-m-d H:i:s');

function ShowsUseObj($query)
{
    global $database;

    $hasil = [];

    $result = $database->query($query);

    while ($fetch = $result->fetch_object()) {
        $hasil[] = $fetch;
    }

    return $hasil;
}

function loginUser($credentials)
{
    global $database, $tanggalWaktuSekarang;

    // define
    $nisn = $credentials["nisn"];
    $password = $credentials["password"];

    // cek nisn
    $checkNisn = $database->query("SELECT nisn FROM voters WHERE nisn = '$nisn'");
    if ($checkNisn->num_rows > 0) {
        // check password
        $checkPassword = $database->query("SELECT password FROM voters WHERE nisn = '$nisn'");
        if ($checkPassword->num_rows > 0) {
            $resultsCheckPassword = $checkPassword->fetch_object();
            if (password_verify($password, $resultsCheckPassword->password)) {
                $_SESSION["loginUser"] = true;
                $_SESSION["dataUser"] = ShowsUseObj("SELECT * FROM voters WHERE nisn = '$nisn'")[0];
                $database->query("UPDATE voters SET latest_login = '$tanggalWaktuSekarang' WHERE nisn = '$nisn'");
                header("Location: ../");
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

function ValidasiNISN($nisn)
{
    // Pastikan hanya angka dan panjang 10 digit
    if (preg_match('/^[0-9]{10}$/', $nisn)) {
        return true;
    } else {
        return false;
    }
}

function tambahVoter($data)
{
    global $database, $tanggalWaktuSekarang;

    // define
    htmlspecialchars($nisn = $data["nisn"]);
    htmlspecialchars($nama = $data["nama"]);
    htmlspecialchars($kelas = $data["kelas"]);
    htmlspecialchars($password = $data["password"]);
    htmlspecialchars($passwordC = $data["passwordC"]);

    // validasi NISN
    if (!ValidasiNISN($nisn)) {
        echo
            "<script>
        alert('NISN tidak valid!')
        </script>
        ";

        return false;
    }

    if (count(ShowsUseObj("SELECT nisn FROM voters WHERE nisn = '$nisn'"))) {
        echo
        "<script>
        alert('NISN sudah terdaftar')
        </script>
        ";

        return false;
    }

    // validasi nama
    if (!is_string($nama)) {
        echo
            "<script>
        alert('Nama mengandung angka!')
        </script>
        ";

        return false;
    }

    // validasi pw
    $password = mysqli_real_escape_string($database, $password);
    if ($password !== $passwordC) {
        echo
            "<script>
        alert('Password tidak sama dengan konfirmasi!')
        </script>
        ";

        return false;
    }

    // encrypt pw
    $password = password_hash($password, PASSWORD_DEFAULT);

    // insert
    $query = $database->prepare("INSERT INTO voters VALUES(NULL, ?, ?, ?, ?, '0', NULL, NULL, NULL, '$tanggalWaktuSekarang')");
    $query->bind_param("ssss", $nisn, $nama, $kelas, $password);
    $query->execute();

    return $database->affected_rows;
}

function tambahBulk($file)
{
    global $database, $tanggalWaktuSekarang;

    $namaFile = $file["bulk"]["name"];
    $tmpName = $file["bulk"]["tmp_name"];

    $ekstensiValid = ["csv", "xlsx", "xls", "ods"];
    $ekstensiUser = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $namaDepan = explode(".", $namaFile);

    if (!in_array($ekstensiUser, $ekstensiValid)) {
        return false;
    }

    move_uploaded_file($tmpName, "../assets/data/$namaFile");

    $fileBulk = "../assets/data/$namaFile";
    $spreadsheet = IOFactory::load($fileBulk);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    foreach ($data as $index => $row) {
        if ($index == 0)
            continue;

        $nisn = $row[0];
        $nama = $row[1];
        $kelas = $row[2];
        $password = $row[3];
        $passwordE = password_hash($password, PASSWORD_DEFAULT);

        $query = $database->prepare("INSERT INTO voters VALUES (NULL, ?, ?, ?, ?, '0', NULL, NULL, NULL, ?)");
        $query->bind_param("sssss", $nisn, $nama, $kelas, $passwordE, $tanggalWaktuSekarang);
        $query->execute();

    }

    return $database->affected_rows;
}

function editVoters($data)
{
    global $database;

    // define
    $id = $data["id"];
    htmlspecialchars($nisn = $data["nisn"]);
    htmlspecialchars($nama = $data["nama"]);
    htmlspecialchars($kelas = $data["kelas"]);

    $query = "UPDATE voters SET
                nama = '$nama',
                nisn = '$nisn',
                kelas = '$kelas'
            WHERE id = '$id'";
    $database->query($query);

    return $database->affected_rows;
}

function tambahKandidat($data) {
    global $database, $tanggalWaktuSekarang;

    // define
    htmlspecialchars($namaKetua = $data["namaK"]);
    htmlspecialchars($namaWakil = $data["namaW"]);
    htmlspecialchars($nisnKetua = $data["nisnK"]);
    htmlspecialchars($nisnWakil = $data["nisnW"]);
    htmlspecialchars($kelasKetua = $data["kelasK"]);
    htmlspecialchars($kelasWakil = $data["kelasW"]);
    $visi = $data["visi"];
    $misi = $data["misi"];
    htmlspecialchars($foto = upload());

    if (!$foto) {
        return false;
    }

    $nama = $namaKetua . ' & ' . $namaWakil;
    $kelas = $kelasKetua . ' & ' . $kelasWakil;

    $insert = $database->prepare("INSERT INTO candidate VALUES(NULL, ?, ?, ?, ?, ?, ?, '$foto', 0, '$tanggalWaktuSekarang')");
    $insert->bind_param("ssssss", $nama, $nisnKetua, $nisnWakil, $kelas, $visi, $misi);
    $insert->execute();

    return $database->affected_rows;
}

function upload() {
    //define
    $namaFile = $_FILES["foto"]["name"];
    $tmpName = $_FILES["foto"]["tmp_name"];
    $error = $_FILES["foto"]["error"];
    $size = $_FILES["foto"]["size"];

    // cek file udah diupload atau belum
    if ($error === 4) {
        echo
            "<script>
        alert('Upload foto dulu!')
        </script>
        ";

        return false;
    }

    // cek ukuran file
    if ($size > 2100152) {
       echo
            "<script>
        alert('Ukuran file terlalu besar!')
        </script>
        ";

        return false;
    }

    // cek ekstensi file
    $ekstensiValid = ["jpg", "png", "jpeg", "webp"];
    $ekstensiUser = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if (!in_array($ekstensiUser, $ekstensiValid)) {
        echo
            "<script>
        alert('Format file tidak valid!')
        </script>
        ";

        return false;
    }

    // upload
    $namaFile = uniqid() . "." . $ekstensiUser;

    move_uploaded_file($tmpName, "../assets/img/$namaFile");

    return $namaFile;
}

function editKandidat($data) {
    global $database, $tanggalWaktuSekarang;

    // define
    $id = $data["id"];
    htmlspecialchars($namaKetua = $data["namaK"]);
    htmlspecialchars($namaWakil = $data["namaW"]);
    htmlspecialchars($nisnKetua = $data["nisnK"]);
    htmlspecialchars($nisnWakil = $data["nisnW"]);
    htmlspecialchars($kelasKetua = $data["kelasK"]);
    htmlspecialchars($kelasWakil = $data["kelasW"]);
    htmlspecialchars($fotoLama = $data["fotoLama"]);
    $visi = $data["visi"];
    $misi = $data["misi"];

    if ($_FILES["foto"]["error"] === 4) {
        $foto = $fotoLama;
    } else {
        $foto = upload();
    }

    $nama = $namaKetua . ' & ' . $namaWakil;
    $kelas = $kelasKetua . ' & ' . $kelasWakil;

    // update
    $query = "UPDATE candidate SET 
            nama = '$nama',
            nisn_ketua = '$nisnKetua',
            nisn_wakil = '$nisnWakil',
            kelas = '$kelas',
            visi = '$visi',
            misi = '$misi',
            foto = '$foto',
            registered = '$tanggalWaktuSekarang' 
            WHERE id = '$id'";
    $database->query($query);

    return $database->affected_rows;
}

function registerAdmin($data) {
    global $database, $tanggalWaktuSekarang;

    // define
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $passwordC = htmlspecialchars($data["passwordC"]);
    $role = htmlspecialchars($data["role"]);

    // validasi username
    $usernameExist = count(ShowsUseObj("SELECT username FROM admin WHERE username = '$username'"));
    if ($usernameExist > 0) {
        echo "
        <script>
        alert('Username telah terdaftar')
        </script>
        ";

        return false;
    }

    if (!is_string($username)) {
        echo "
        <script>
        alert('Username tidak valid!')
        </script>
        ";

        return false;
    }

    // validasi password
    $database->real_escape_string($password);
    if ($password !== $passwordC) {
        echo "
        <script>
        alert('Password tidak sama dengan konfirmasi')
        </script>
        ";

        return false;
    }

    // encrypt password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // insert
    $insert = $database->prepare("INSERT INTO admin VALUES(NULL, ?, ?, NULL, '$tanggalWaktuSekarang', ?)");
    $insert->bind_param("sss", $username, $password, $role);
    $insert->execute();

    return $database->affected_rows;
}

function loginAdmin($credentials) {
    global $database, $tanggalWaktuSekarang;

    // define
    $username = $credentials["username"];
    $password = $credentials["password"];

    // cek username
    $checkUsername = ShowsUseObj("SELECT * FROM admin WHERE username = '$username'");
    if (count($checkUsername) > 0) {
        // cek password
        $checkPassword = ShowsUseObj("SELECT password FROM admin WHERE username = '$username'")[0];
        if (password_verify($password, $checkPassword->password)) {
            // cek role
            $checkRole = ShowsUseObj("SELECT role FROM admin WHERE username = '$username' AND password = '$checkPassword->password'")[0];
            if ($checkRole->role == "admin") {
                $_SESSION["loginAdmin"] = true;
                $_SESSION["dataUser"] = ShowsUseObj("SELECT * FROM admin WHERE username = '$username'")[0];
                $database->query("UPDATE admin SET latest_login = '$tanggalWaktuSekarang' WHERE username = '$username'");
                header("Location: ../admin.php");
            } else if ($checkRole->role == "superadmin") {
                $_SESSION["loginAdmin"] = true;
                $_SESSION["superadmin"] = true;
                $_SESSION["dataUser"] = ShowsUseObj("SELECT * FROM admin WHERE username = '$username'")[0];
                $database->query("UPDATE admin SET latest_login = '$tanggalWaktuSekarang' WHERE username = '$username'");
                header("Location: ../admin.php");
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
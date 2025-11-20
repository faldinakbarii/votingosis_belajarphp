<?php
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["superadmin"])) {
    header("Location: ../admin.php");
    exit;
}

require '../../include/functions.php';

if (isset($_POST["register"])) {
    $register = registerAdmin($_POST);
    if ($register > 0) {
        echo "
        <script>
        alert('Register admin baru berhasil!')
        document.location.href = '../admin.php'
        </script>
        ";
    } else {
        echo "
        <script>
        alert('Register admin baru gagal!')
        document.location.href = '../admin.php'
        </script>
        ";
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Admin - Vote OSIS 2025</title>
    <link rel="stylesheet" href="../../assets/css/registerAdmin.css">
</head>

<body>
    <div class="background-circles">
        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>
    </div>

    <div class="register-container">
        <div class="register-left">
            <div class="icon">🎯</div>
            <h1>Daftar Sebagai Admin</h1>
            <p>Kelola sistem voting OSIS dengan mudah dan professional</p>

            <div class="features">
                <div class="feature-item">
                    <span class="icon-small">✅</span>
                    <span>Kelola data pemilih dengan mudah</span>
                </div>
                <div class="feature-item">
                    <span class="icon-small">✅</span>
                    <span>Dashboard yang informatif</span>
                </div>
                <div class="feature-item">
                    <span class="icon-small">✅</span>
                    <span>Export data ke Excel/PDF</span>
                </div>
            </div>
        </div>

        <div class="register-right">
            <h2>Buat Akun Admin</h2>
            <p class="subtitle">Isi data di bawah untuk membuat akun admin baru</p>

            <div class="error-message" id="errorMessage">
                ⚠️ Terjadi kesalahan! Silahkan cek kembali data Anda.
            </div>

            <div class="success-message" id="successMessage">
                ✅ Registrasi berhasil! Silahkan login.
            </div>

            <form id="registerForm" action="" method="POST">
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">🆔</span>
                        <input type="text" id="username" class="form-control" placeholder="Pilih username unik" required
                            name="username" minlength="5">
                    </div>
                    <p class="password-hint">Minimal 5 karakter, tanpa spasi</p>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" class="form-control" placeholder="Buat password kuat"
                            required name="password" minlength="8" oninput="checkPasswordStrength()">
                        <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <p class="password-hint">Minimal 8 karakter, kombinasi huruf, angka, dan simbol</p>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Konfirmasi Password <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="confirmPassword" class="form-control" placeholder="Ulangi password"
                            required name="passwordC" minlength="8">
                        <span class="password-toggle" onclick="togglePassword('confirmPassword')">👁️</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Role/Jabatan <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">🎓</span>
                        <select id="role" class="form-control" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        Saya setuju dengan <a href="#" onclick="showTerms(event)">Syarat & Ketentuan</a> dan <a href="#"
                            onclick="showPrivacy(event)">Kebijakan Privasi</a>
                    </label>
                </div>

                <button type="submit" class="register-btn" name="register" id="registerBtn">
                    <span>Daftar Sekarang</span>
                </button>
            </form>
            <div class="divider">
                <span>atau</span>
            </div>

            <div class="login-link">
                Kembali? <a href="../admin.php">Ke dashboard</a>
            </div>
        </div>
    </div>

    <script src="../../assets/js/registerAdmin.js">

    </script>
</body>

</html>
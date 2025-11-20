<?php 
require __DIR__ . '/../include/functions.php';

if (isset($_POST["login"])) {
    $loginUser = loginUser($_POST);
    if (!$loginUser) {
        echo "
        <script>
        alert('Login gagal! nisn atau password salah!');
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
    <title>Login - Vote OSIS 2025</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="background-circles">
        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="icon">🔐</div>
            <h1>Silahkan Login</h1>
            <p>Pemilihan Ketua OSIS 2025</p>
        </div>

        <div class="login-body">
            <div class="error-message" id="errorMessage">
                ⚠️ Username atau password salah!
            </div>

            <form id="loginForm" action="" method="POST">
                <div class="form-group">
                    <label for="username">NISN (Nomor Induk Siswa Nasional)</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="username" class="form-control" name="nisn" placeholder="Masukkan NISN" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" class="form-control" name="password" placeholder="Masukkan password"
                            required>
                        <span class="password-toggle" onclick="togglePassword()">👁️</span>
                    </div>
                </div>

                <button type="submit" class="login-btn" name="login">
                    <span>Login</span>
                </button>
            </form>

        </div>
    </div>

    <script src="../assets/js/login.js" type="text/javascript">
    </script>

</body>

</html>
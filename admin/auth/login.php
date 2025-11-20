<?php 
session_start();
require '../../include/functions.php';

if (isset($_POST["login"])) {
    $login = loginAdmin($_POST);
    if (!$login) {
        echo "
        <script>
        alert('Login gagal, username atau password salah!')
        document.location.href = 'login.php'
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
    <title>Login Admin - Vote OSIS 2025</title>
    <link rel="stylesheet" href="../../assets/css/loginAdmin.css">
</head>
<body>
    <div class="background-circles">
        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>
        <div class="circle circle4"></div>
    </div>

    <div class="login-container">

        <div class="login-right">
            <h2>Selamat Datang! 👋</h2>
            <p class="subtitle">Silahkan login untuk mengakses dashboard admin</p>

            <div class="error-message" id="errorMessage">
                ⚠️ Username atau password salah!
            </div>

            <form id="loginForm" action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input 
                            type="text" 
                            id="username" 
                            class="form-control" 
                            placeholder="Masukkan username"
                            name="username"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input 
                            type="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Masukkan password"
                            name="password"
                            required
                        >
                        <span class="password-toggle" onclick="togglePassword()">👁️</span>
                    </div>
                </div>

                <button type="submit" class="login-btn" name="login">
                    <span>Login</span>
                </button>
            </form>

            <div class="divider">
                <span>atau</span>
            </div>

            <div class="back-link">
                <a href="../../">
                    ← Kembali ke Halaman Vote
                </a>
            </div>
        </div>
    </div>

    <script src="../../assets/js/loginAdmin.js">

    </script>
</body>
</html>
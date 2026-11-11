
<?php

require_once "config/koneksi.php";
require_once "classes/Auth.php";

$auth = new Auth($conn);

if ($auth->checkLogin()) {

    if ($auth->checkRole('admin')) {
        header("Location: admin/dashboard.php");
        exit;
    }

    if ($auth->checkRole('petugas')) {
        header("Location: petugas/dashboard.php");
        exit;
    }
}

$error = $_GET['error'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login -Aplikasi Kasir</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="login-container">

        <div class="login-box">

            <h2>Aplikasi Kasir</h2>
            <p>Silakan login untuk melanjutkan</p>

            <?php if ($error === 'invalid'): ?>
                <div class="alert">
                    Username atau password salah
                </div>
            <?php elseif ($error === 'empty'): ?>
                <div class="alert">
                    Username dan password wajib diisi
                </div>
            <?php elseif ($error === 'role'): ?>
                <div class="alert">
                    Role pengguna tidak valid
                </div>
            <?php endif; ?> 

            <form action="proses/login.php" method="POST">

                <div class="form-group">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

            </form>

        </div>

    </div>

</body>
</html>

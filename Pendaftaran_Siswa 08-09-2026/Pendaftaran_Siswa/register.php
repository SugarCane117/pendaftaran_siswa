<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header('Location: dashboard.php');
    exit;
}

mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS tb_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $username)) {
        $error = 'Username harus 4-50 karakter dan hanya boleh berisi huruf, angka, atau garis bawah.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $password_confirmation) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $check = mysqli_prepare($koneksi, 'SELECT id FROM tb_users WHERE username = ?');
        mysqli_stmt_bind_param($check, 's', $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0 || $username === 'admin') {
            $error = 'Username sudah digunakan.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($koneksi, 'INSERT INTO tb_users (username, password) VALUES (?, ?)');
            mysqli_stmt_bind_param($insert, 'ss', $username, $hashed_password);

            if (mysqli_stmt_execute($insert)) {
                header('Location: login.php?registered=1');
                exit;
            }

            $error = 'Registrasi gagal. Silakan coba lagi.';
            mysqli_stmt_close($insert);
        }

        mysqli_stmt_close($check);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f1f5f9; font-family: Arial, sans-serif; color: #0f172a; }
        .register-box { width: 100%; max-width: 380px; margin: 20px; padding: 30px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
        h1 { margin: 0 0 8px; font-size: 24px; text-align: center; }
        .subtitle { margin: 0 0 24px; color: #64748b; text-align: center; font-size: 14px; }
        label { display: block; margin: 14px 0 6px; font-size: 14px; font-weight: bold; }
        input { width: 100%; padding: 11px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        button { width: 100%; margin-top: 22px; padding: 11px; border: 0; border-radius: 6px; background: #2563eb; color: #fff; font-size: 14px; font-weight: bold; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { padding: 10px; border-radius: 6px; background: #fee2e2; color: #b91c1c; font-size: 13px; }
        .login-link { margin: 18px 0 0; text-align: center; font-size: 14px; }
        a { color: #2563eb; }
    </style>
</head>
<body>
    <main class="register-box">
        <h1>Register Akun</h1>
        <p class="subtitle">Buat akun untuk mengakses sistem</p>
        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" minlength="4" maxlength="50" required autofocus>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" minlength="6" required>
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" minlength="6" required>
            <button type="submit">Daftar</button>
        </form>
        <p class="login-link">Sudah punya akun? <a href="login.php">Login</a></p>
    </main>
</body>
</html>

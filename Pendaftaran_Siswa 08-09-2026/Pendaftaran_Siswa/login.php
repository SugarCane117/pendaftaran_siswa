<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header('Location: dashboard.php');
    exit;
}
include 'koneksi.php';

$error = '';
$success = isset($_GET['registered']) ? 'Registrasi berhasil. Silakan login.' : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $is_valid_login = $username === 'admin' && $password === 'admin123';

    if (!$is_valid_login) {
        $query = mysqli_prepare($koneksi, 'SELECT password FROM tb_users WHERE username = ?');
        if ($query) {
            mysqli_stmt_bind_param($query, 's', $username);
            mysqli_stmt_execute($query);
            mysqli_stmt_bind_result($query, $hashed_password);
            if (mysqli_stmt_fetch($query)) {
                $is_valid_login = password_verify($password, $hashed_password);
            }
            mysqli_stmt_close($query);
        }
    }

    if ($is_valid_login) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pendaftaran Siswa</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f1f5f9; font-family: Arial, sans-serif; color: #0f172a; }
        .login-box { width: 100%; max-width: 380px; margin: 20px; padding: 30px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08); }
        h1 { margin: 0 0 8px; font-size: 24px; text-align: center; }
        .subtitle { margin: 0 0 24px; color: #64748b; text-align: center; font-size: 14px; }
        label { display: block; margin: 14px 0 6px; font-size: 14px; font-weight: bold; }
        input { width: 100%; padding: 11px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        button { width: 100%; margin-top: 22px; padding: 11px; border: 0; border-radius: 6px; background: #2563eb; color: #fff; font-size: 14px; font-weight: bold; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { padding: 10px; border-radius: 6px; background: #fee2e2; color: #b91c1c; font-size: 13px; }
        .success { padding: 10px; border-radius: 6px; background: #dcfce7; color: #166534; font-size: 13px; }
        .register-link { margin: 18px 0 0; text-align: center; font-size: 14px; }
        a { color: #2563eb; }
    </style>
</head>
<body>
    <main class="login-box">
        <h1>Login Admin</h1>
        <p class="subtitle">Sistem Pendaftaran Siswa</p>
        <?php if ($error !== ''): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if ($success !== ''): ?>
            <div class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" required autofocus>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            <button type="submit">Masuk</button>
        </form>
        <p class="register-link">Belum punya akun? <a href="register.php">Register</a></p>
    </main>
</body>
</html>

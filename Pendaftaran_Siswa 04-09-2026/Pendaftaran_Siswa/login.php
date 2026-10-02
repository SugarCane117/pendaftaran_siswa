<?php
include 'auth.php';
sudahLogin();

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($koneksi, 'SELECT id, nama, password FROM tb_users WHERE username = ?');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($hasil);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama_user'] = $user['nama'];
        header('Location: index.php');
        exit();
    }

    $pesan = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pendaftaran Siswa Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .container { max-width: 420px; margin: 60px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-blue { background-color: #007bff; width: 100%; }
        .link { text-align: center; margin: 18px 0 0; font-size: 14px; }
        .link a { color: #007bff; text-decoration: none; }
        .error { color: #dc3545; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <p>Masuk untuk mengelola pendaftaran siswa.</p>
    <?php if ($pesan): ?><div class="error"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-blue">Masuk</button>
    </form>
    <p class="link">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
</div>
</body>
</html>
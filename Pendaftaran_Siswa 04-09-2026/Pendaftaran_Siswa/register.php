<?php
include 'auth.php';
sudahLogin();

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if ($nama === '' || $username === '' || strlen($password) < 6) {
        $pesan = 'Nama dan username wajib diisi. Password minimal 6 karakter.';
    } elseif ($password !== $konfirmasi) {
        $pesan = 'Konfirmasi password tidak cocok.';
    } else {
        $stmt = mysqli_prepare($koneksi, 'SELECT id FROM tb_users WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $sudahAda = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($sudahAda) > 0) {
            $pesan = 'Username sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($koneksi, 'INSERT INTO tb_users (nama, username, password) VALUES (?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sss', $nama, $username, $hash);
            mysqli_stmt_execute($stmt);
            header('Location: login.php?daftar=berhasil');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Pendaftaran Siswa Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .container { max-width: 420px; margin: 60px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-green { background-color: #28a745; width: 100%; }
        .link { text-align: center; margin: 18px 0 0; font-size: 14px; }
        .link a { color: #007bff; text-decoration: none; }
        .error { color: #dc3545; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Daftar Akun</h2>
    <p>Buat akun untuk mengakses pendaftaran siswa.</p>
    <?php if ($pesan): ?><div class="error"><?php echo htmlspecialchars($pesan); ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="6" required>
        </div>
        <div class="form-group">
            <label for="konfirmasi">Konfirmasi Password</label>
            <input type="password" id="konfirmasi" name="konfirmasi" minlength="6" required>
        </div>
        <button type="submit" class="btn btn-green">Daftar</button>
    </form>
    <p class="link">Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>
</div>
</body>
</html>
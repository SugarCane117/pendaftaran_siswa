<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';
if (isset($_POST['tambah'])) {
    $nama_jurusan = mysqli_real_escape_string($koneksi, $_POST['nama_jurusan']);
    mysqli_query($koneksi, "INSERT INTO tb_jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
    header('Location: jurusan.php'); exit;
}
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM tb_jurusan WHERE id_jurusan='$id'");
    header('Location: jurusan.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Master Data Jurusan</title>
    <style>
        :root { --bg-body:#f8fafc; --sidebar-bg:#0f172a; --sidebar-hover:#1e293b; --border:#e2e8f0; --text-dark:#0f172a; --text-muted:#64748b; }
        * { box-sizing:border-box; margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:var(--bg-body); color:var(--text-dark); display:flex; min-height:100vh; }
        .sidebar { width:250px; background:var(--sidebar-bg); color:#fff; padding:20px 15px; display:flex; flex-direction:column; }
        .brand { font-size:16px; font-weight:bold; padding-bottom:20px; border-bottom:1px solid #334155; color:#38bdf8; text-transform:uppercase; letter-spacing:.5px; }
        .nav-menu { margin-top:20px; list-style:none; } .nav-item { margin-bottom:5px; }
        .nav-link { display:block; padding:10px 14px; color:#94a3b8; text-decoration:none; border-radius:6px; font-size:14px; transition:.2s; }
        .nav-link:hover,.nav-link.active { background:var(--sidebar-hover); color:#fff; font-weight:500; }
        .logout-link { margin-top:auto; background:#dc2626; color:#fff; text-align:center; } .logout-link:hover { background:#b91c1c; color:#fff; }
        .main-content { flex:1; padding:25px 30px; } .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; } .topbar h1 { font-size:22px; }
        .user-profile { font-size:13px; color:var(--text-muted); background:#fff; padding:6px 14px; border-radius:20px; border:1px solid var(--border); }
        .panel { max-width:700px; background:#fff; border-radius:10px; border:1px solid var(--border); padding:20px; box-shadow:0 1px 3px rgba(0,0,0,.05); }
        .form-row { display:flex; gap:10px; margin-bottom:15px; } input { flex:1; padding:9px 12px; border:1px solid var(--border); border-radius:6px; }
        .btn { padding:8px 16px; border:0; border-radius:6px; color:#fff; cursor:pointer; text-decoration:none; font-size:13px; } .btn-green { background:#16a34a; } .btn-danger { background:#dc2626; }
        table { width:100%; border-collapse:collapse; margin-top:15px; font-size:13px; } th,td { padding:12px; text-align:left; border-bottom:1px solid var(--border); } th { background:#f1f5f9; color:#475569; }
        @media (max-width:700px) { body { display:block; } .sidebar { width:100%; } .main-content { padding:20px 15px; } .form-row { flex-wrap:wrap; } }
    </style>
</head>
<body>
    <aside class="sidebar"><div class="brand">SMK Tunas Media App</div><ul class="nav-menu"><li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard Utama</a></li><li class="nav-item"><a href="siswa.php" class="nav-link">Pendaftaran Siswa Baru</a></li><li class="nav-item"><a href="jurusan.php" class="nav-link active">Master Data Jurusan</a></li></ul><a href="logout.php" class="nav-link logout-link">Logout</a></aside>
    <main class="main-content"><header class="topbar"><h1>Master Data Jurusan</h1><div class="user-profile">Petugas: <strong><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></strong></div></header>
        <section class="panel"><form method="POST" class="form-row"><input type="text" name="nama_jurusan" placeholder="Nama Jurusan Baru" required><button type="submit" name="tambah" class="btn btn-green">Tambah Jurusan</button></form>
        <table><tr><th>ID</th><th>Nama Jurusan</th><th>Aksi</th></tr><?php $q=mysqli_query($koneksi,"SELECT * FROM tb_jurusan"); while($r=mysqli_fetch_assoc($q)){ echo "<tr><td>{$r['id_jurusan']}</td><td>{$r['nama_jurusan']}</td><td><a href='jurusan.php?hapus={$r['id_jurusan']}' class='btn btn-danger' onclick='return confirm(\"Hapus jurusan ini?\")'>Hapus</a></td></tr>"; } ?></table></section>
    </main>
</body>
</html>

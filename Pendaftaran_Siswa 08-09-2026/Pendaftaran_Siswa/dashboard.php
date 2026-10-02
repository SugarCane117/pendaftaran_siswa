<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_siswa"))['total'];
$total_jurusan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_jurusan"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; }
        .stat-card { background:#fff; padding:20px; border-radius:10px; border:1px solid var(--border); box-shadow:0 1px 3px rgba(0,0,0,.05); }
        .stat-card p { font-size:13px; color:var(--text-muted); font-weight:500; } .stat-card h2 { font-size:28px; margin-top:5px; }
        @media (max-width:700px) { body { display:block; } .sidebar { width:100%; } .main-content { padding:20px 15px; } }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">SMK Tunas Media App</div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link active">Dashboard Utama</a></li>
            <li class="nav-item"><a href="siswa.php" class="nav-link">Pendaftaran Siswa Baru</a></li>
            <li class="nav-item"><a href="jurusan.php" class="nav-link">Master Data Jurusan</a></li>
        </ul>
        <a href="logout.php" class="nav-link logout-link">Logout</a>
    </aside>
    <main class="main-content">
        <header class="topbar"><h1>Dashboard</h1><div class="user-profile">Petugas: <strong><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></strong></div></header>
        <section class="stats-grid">
            <div class="stat-card"><p>Total Siswa Terdaftar</p><h2><?php echo $total_siswa; ?></h2></div>
            <div class="stat-card"><p>Jumlah Jurusan Tersedia</p><h2><?php echo $total_jurusan; ?></h2></div>
        </section>
    </main>
</body>
</html>

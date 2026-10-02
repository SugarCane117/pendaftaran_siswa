<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru</title>
    <style>
        :root { --bg-body:#f8fafc; --sidebar-bg:#0f172a; --sidebar-hover:#1e293b; --card-bg:#fff; --primary:#2563eb; --primary-hover:#1d4ed8; --text-dark:#0f172a; --text-muted:#64748b; --border:#e2e8f0; }
        * { box-sizing:border-box; margin:0; padding:0; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background:var(--bg-body); color:var(--text-dark); display:flex; min-height:100vh; }
        .sidebar { width:250px; background:var(--sidebar-bg); color:#fff; padding:20px 15px; display:flex; flex-direction:column; }
        .brand { font-size:16px; font-weight:bold; padding-bottom:20px; border-bottom:1px solid #334155; color:#38bdf8; text-transform:uppercase; letter-spacing:.5px; }
        .nav-menu { margin-top:20px; list-style:none; }
        .nav-item { margin-bottom:5px; }
        .nav-link { display:block; padding:10px 14px; color:#94a3b8; text-decoration:none; border-radius:6px; font-size:14px; transition:.2s; }
        .nav-link:hover, .nav-link.active { background:var(--sidebar-hover); color:#fff; font-weight:500; }
        .logout-link { margin-top:auto; background:#dc2626; color:#fff; text-align:center; }
        .logout-link:hover { background:#b91c1c; color:#fff; }
        .main-content { flex:1; padding:25px 30px; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
        .topbar h1 { font-size:22px; }
        .user-profile { font-size:13px; color:var(--text-muted); background:#fff; padding:6px 14px; border-radius:20px; border:1px solid var(--border); }
        .panel { background:var(--card-bg); border-radius:10px; border:1px solid var(--border); padding:20px; box-shadow:0 1px 3px rgba(0,0,0,.05); }
        .panel-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
        .panel-title { font-size:16px; font-weight:600; }
        .btn { padding:8px 16px; border:0; border-radius:6px; font-size:13px; font-weight:500; cursor:pointer; text-decoration:none; display:inline-block; }
        .btn-primary { background:var(--primary); color:#fff; } .btn-primary:hover { background:var(--primary-hover); }
        .btn-danger { background:#ef4444; color:#fff; } .btn-blue { background:#0284c7; color:#fff; } .btn-secondary { background:#e2e8f0; color:var(--text-dark); }
        .filter-row { display:flex; gap:10px; margin-bottom:15px; }
        .form-control { width:100%; padding:8px 12px; border:1px solid var(--border); border-radius:6px; font-size:13px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; font-size:13px; } th,td { padding:12px; text-align:left; border-bottom:1px solid var(--border); } th { background:#f1f5f9; color:#475569; font-weight:600; }
        .img-thumb { width:42px; height:50px; object-fit:cover; border-radius:4px; }
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); justify-content:center; align-items:center; z-index:100; }
        .modal-box { background:#fff; padding:25px; border-radius:12px; width:100%; max-width:550px; box-shadow:0 10px 25px rgba(0,0,0,.15); }
        .modal-header,.modal-footer { display:flex; justify-content:space-between; align-items:center; } .modal-header { margin-bottom:15px; } .modal-footer { justify-content:flex-end; gap:10px; margin-top:20px; }
        @media (max-width:700px) { body { display:block; } .sidebar { width:100%; } .main-content { padding:20px 15px; } .filter-row { flex-wrap:wrap; } table { display:block; overflow-x:auto; white-space:nowrap; } }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">SMK Tunas Media App</div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard Utama</a></li>
            <li class="nav-item"><a href="siswa.php" class="nav-link active">Pendaftaran Siswa Baru</a></li>
            <li class="nav-item"><a href="jurusan.php" class="nav-link">Master Data Jurusan</a></li>
        </ul>
        <a href="logout.php" class="nav-link logout-link">Logout</a>
    </aside>
    <main class="main-content">
        <header class="topbar">
            <h1>Pendaftaran Siswa Baru</h1>
            <div class="user-profile">Petugas: <strong><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
        </header>
        <section class="panel">
            <div class="panel-header"><div class="panel-title">Daftar Calon Siswa Baru</div><button class="btn btn-primary" onclick="openModal()">+ Tambah Siswa Baru</button></div>
            <form method="GET" class="filter-row">
                <input type="text" name="keyword" class="form-control" placeholder="Cari NIS atau Nama..." value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <select name="filter_jurusan" class="form-control" style="max-width:200px;"><option value="">Semua Jurusan</option>
                    <?php $j_query=mysqli_query($koneksi,"SELECT * FROM tb_jurusan"); while($j_row=mysqli_fetch_assoc($j_query)){ $selected=(($_GET['filter_jurusan']??'')==$j_row['id_jurusan'])?'selected':''; echo "<option value='{$j_row['id_jurusan']}' $selected>{$j_row['nama_jurusan']}</option>"; } ?>
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button><a href="siswa.php" class="btn btn-secondary">Reset</a>
            </form>
            <table><thead><tr><th>Pasfoto</th><th>NIS</th><th>Nama Lengkap</th><th>Pilihan Jurusan</th><th>Alasan Masuk</th><th style="width:160px">Aksi</th></tr></thead><tbody>
            <?php
            $sql="SELECT tb_siswa.*,tb_jurusan.nama_jurusan FROM tb_siswa JOIN tb_jurusan ON tb_siswa.id_jurusan=tb_jurusan.id_jurusan WHERE 1=1";
            if (!empty($_GET['keyword'])) { $kw=mysqli_real_escape_string($koneksi,$_GET['keyword']); $sql.=" AND (tb_siswa.nama LIKE '%$kw%' OR tb_siswa.nis LIKE '%$kw%')"; }
            if (!empty($_GET['filter_jurusan'])) { $fj=mysqli_real_escape_string($koneksi,$_GET['filter_jurusan']); $sql.=" AND tb_siswa.id_jurusan='$fj'"; }
            $sql.=" ORDER BY tb_siswa.id DESC"; $result=mysqli_query($koneksi,$sql);
            if (mysqli_num_rows($result)>0) { while($row=mysqli_fetch_assoc($result)) { echo "<tr><td><img src='uploads/{$row['foto']}' class='img-thumb'></td><td><strong>{$row['nis']}</strong></td><td>{$row['nama']}</td><td>{$row['nama_jurusan']}</td><td>{$row['alasan']}</td><td><a href='cetak.php?id={$row['id']}' target='_blank' class='btn btn-blue' style='padding:5px 10px'>Cetak</a> <a href='hapus.php?id={$row['id']}' class='btn btn-danger' style='padding:5px 10px' onclick='return confirm(\"Hapus data siswa ini?\")'>Hapus</a></td></tr>"; } } else { echo "<tr><td colspan='6' style='text-align:center;color:#94a3b8;padding:20px'>Data siswa tidak ditemukan.</td></tr>"; }
            ?>
            </tbody></table>
        </section>
    </main>
    <div class="modal-overlay" id="modalForm"><div class="modal-box"><div class="modal-header"><h3>Form Pendaftaran Siswa Baru</h3><button type="button" onclick="closeModal()" style="border:0;background:none;font-size:18px;cursor:pointer">&times;</button></div>
        <form action="simpan.php" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom:12px"><label>NIS:</label><input type="text" name="nis" class="form-control" required></div>
            <div style="margin-bottom:12px"><label>Nama Lengkap:</label><input type="text" name="nama" class="form-control" required></div>
            <div style="margin-bottom:12px"><label>Pilihan Jurusan:</label><select name="id_jurusan" class="form-control" required><option value="">-- Pilih Jurusan --</option><?php $j_list=mysqli_query($koneksi,"SELECT * FROM tb_jurusan"); while($jl=mysqli_fetch_assoc($j_list)){ echo "<option value='{$jl['id_jurusan']}'>{$jl['nama_jurusan']}</option>"; } ?></select></div>
            <div style="margin-bottom:12px"><label>Pasfoto (JPG/PNG, Max 2MB):</label><input type="file" name="foto" class="form-control" accept="image/*" required></div>
            <div style="margin-bottom:12px"><label>Alasan Masuk:</label><textarea name="alasan" class="form-control" rows="2" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button><button type="submit" class="btn btn-primary">Simpan Data</button></div>
        </form>
    </div></div>
    <script>function openModal(){document.getElementById('modalForm').style.display='flex'} function closeModal(){document.getElementById('modalForm').style.display='none'}</script>
</body>
</html>

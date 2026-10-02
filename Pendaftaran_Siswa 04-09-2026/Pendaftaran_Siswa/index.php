<?php
include 'auth.php';
wajibLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Siswa Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; gap: 15px; }
        .header h2 { margin-top: 0; }
        .user-info { color: #555; font-size: 14px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; 
text-decoration: none; font-size: 13px; display: inline-block; }
        .btn-green { background-color: #28a745; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; }
        .btn-secondary { background-color: #6c757d; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .error { color: red; font-size: 0.85em; display: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h2>Form Pendaftaran Siswa Baru</h2>
            <span class="user-info">Login sebagai <?php echo htmlspecialchars($_SESSION['nama_user']); ?></span>
        </div>
        <a href="logout.php" class="btn btn-secondary">Keluar</a>
    </div>
    <form action="simpan.php" method="POST" onsubmit="return validasiForm()">
        <div class="form-group">
            <label>NIS:</label>
            <input type="text" id="nis" name="nis">
            <span id="errNis" class="error">NIS wajib diisi angka!</span>
        </div>
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" id="nama" name="nama">
            <span id="errNama" class="error">Nama wajib diisi!</span>
        </div>
        <div class="form-group">
            <label>Jurusan:</label>
            <select name="jurusan">
                <option value="RPL">Rekayasa Perangkat Lunak</option>
                <option value="TKJ">Teknik Komputer & Jaringan</option>
                <option value="MM">Multimedia</option>
            </select>
        </div>
        <div class="form-group">
            <label>Alasan Masuk:</label>
            <textarea name="alasan" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-green">Daftar Siswa</button>
    </form>
    <hr style="margin: 30px 0;">
    <h2>Daftar Siswa Terdaftar</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Alasan</th> <th>Aksi (Koneksi Form)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM tb_siswa ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nis']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['jurusan']}</td>
                        <td>{$row['alasan']}</td>
                        <td>
                            <a href='edit.php?id={$row['id']}' class='btn btn-warning'>Edit</a> 
                            <a href='hapus.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(&quot;Hapus data ini?&quot;)'>Hapus</a>
                        </td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>
<script>
function validasiForm() {
    let nis = document.getElementById("nis").value;
    let nama = document.getElementById("nama").value;
    let valid = true;
    if (nis.trim() === "" || isNaN(nis)) {
        document.getElementById("errNis").style.display = "block";
        valid = false;
    } else {
        document.getElementById("errNis").style.display = "none";
    }
    if (nama.trim() === "") {
        document.getElementById("errNama").style.display = "block";
        valid = false;
    } else {
        document.getElementById("errNama").style.display = "none";
    }
    return valid;
}
</script>
</body>
</html>
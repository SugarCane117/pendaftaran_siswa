<?php
include 'auth.php';
wajibLogin();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis     = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $alasan  = mysqli_real_escape_string($koneksi, $_POST['alasan']);
    $sql = "INSERT INTO tb_siswa (nis, nama, jurusan, alasan) VALUES ('$nis', '$nama', '$jurusan', 
'$alasan')";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
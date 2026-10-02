<?php
include 'koneksi.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);
    $foto_lama = $data['foto'];
    if (file_exists("uploads/" . $foto_lama)) {
        unlink("uploads/" . $foto_lama);
    }
    $sql = "DELETE FROM tb_siswa WHERE id='$id'";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: siswa.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
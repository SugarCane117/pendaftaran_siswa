<?php
include 'auth.php';
wajibLogin();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM tb_siswa WHERE id='$id'";
    if (mysqli_query($koneksi, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
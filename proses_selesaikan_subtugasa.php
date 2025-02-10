<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subtugas_id'])) {
    include("koneksi.php");  // Pastikan koneksi dimuat

    $subtugas_id = $_POST['subtugas_id'];

    // Dapatkan tugas_id dari subtugas yang dicentang
    $sql_get_tugas = "SELECT tugas_id FROM subtugas WHERE id = $subtugas_id";
    $result_get_tugas = mysqli_query($db, $sql_get_tugas);
    $row = mysqli_fetch_assoc($result_get_tugas);
    $tugas_id = $row['tugas_id'];

    // Perbarui status subtugas menjadi selesai
    $sql_update_subtugas = "UPDATE subtugas SET status = 'selesai' WHERE id = $subtugas_id";
    mysqli_query($db, $sql_update_subtugas);

    // Cek apakah masih ada subtugas yang belum selesai
    $sql_cek_semua = "SELECT COUNT(*) AS belum_selesai FROM subtugas WHERE tugas_id = $tugas_id AND status != 'selesai'";
    $result_cek_semua = mysqli_query($db, $sql_cek_semua);
    $data = mysqli_fetch_assoc($result_cek_semua);

    if ($data['belum_selesai'] == 0) {
        // Jika semua subtugas selesai, set tugas menjadi selesai
        $sql_update_tugas = "UPDATE tugas SET status = 1 WHERE id = $tugas_id";
        mysqli_query($db, $sql_update_tugas);
    }
}

header("Location: halaman.php");
exit;
?>

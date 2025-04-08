<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$subtugas_id = $_GET['subtugas_id'];
$status = $_GET['status'] == '1' ? 'selesai' : 'belum';
$tugas_id = $_GET['tugas_id'];

mysqli_query($db, "UPDATE subtugas SET status = '$status' WHERE id = $subtugas_id 
    AND tugas_id IN (SELECT id FROM tugas WHERE user_id = $user_id)");

$sql_subtugas = "SELECT COUNT(*) as total FROM subtugas WHERE tugas_id = $tugas_id AND status != 'selesai'";
$result_subtugas = mysqli_query($db, $sql_subtugas);
$row = mysqli_fetch_assoc($result_subtugas);

if ($row['total'] == 0) {
    mysqli_query($db, "UPDATE tugas SET status = 1 WHERE id = $tugas_id AND user_id = $user_id");
    echo 'completed';
} else {
    echo 'not_completed';
}
?>
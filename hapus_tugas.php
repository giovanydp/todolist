<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: halaman.php");
    exit;
}

$tugas_id = $_GET['id'];

// Hapus subtugas terlebih dahulu
$sql_hapus_subtugas = "DELETE FROM subtugas WHERE tugas_id = ? AND EXISTS (SELECT 1 FROM tugas WHERE id = ? AND user_id = ?)";
$stmt_hapus_subtugas = $db->prepare($sql_hapus_subtugas);
$stmt_hapus_subtugas->bind_param("iii", $tugas_id, $tugas_id, $user_id);
$stmt_hapus_subtugas->execute();

// Hapus tugas
$sql_hapus_tugas = "DELETE FROM tugas WHERE id = ? AND user_id = ?";
$stmt_hapus_tugas = $db->prepare($sql_hapus_tugas);
$stmt_hapus_tugas->bind_param("ii", $tugas_id, $user_id);

if ($stmt_hapus_tugas->execute()) {
    header("Location: halaman.php");
    exit;
} else {
    echo "Gagal menghapus tugas.";
}
?>

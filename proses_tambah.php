<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    die("Error: Pengguna tidak terautentikasi.");
}

$user_id = $_SESSION['user_id'];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_tugas = $_POST["nama_tugas"];
    $deskripsi = $_POST["deskripsi"];
    $tenggat = $_POST["tenggat"];
    $subtugas = isset($_POST["subtugas"]) ? $_POST["subtugas"] : [];

    $sql_tugas = "INSERT INTO tugas (user_id, nama_tugas, deskripsi, tenggat) VALUES (?, ?, ?, ?)";
    $stmt_tugas = $db->prepare($sql_tugas);
    $stmt_tugas->bind_param("isss", $user_id, $nama_tugas, $deskripsi, $tenggat);

    if ($stmt_tugas->execute()) {
        $tugas_id = $stmt_tugas->insert_id;

        if (!empty($subtugas)) {
            $sql_subtugas = "INSERT INTO subtugas (tugas_id, nama_subtugas) VALUES (?, ?)";
            $stmt_subtugas = $db->prepare($sql_subtugas);

            foreach ($subtugas as $sub) {
                $stmt_subtugas->bind_param("is", $tugas_id, $sub);
                $stmt_subtugas->execute();
            }
        }

        header("Location: halaman.php?status=sukses");
        exit;
    } else {
        $error_message = "Gagal menambahkan tugas.";
    }
}
?>

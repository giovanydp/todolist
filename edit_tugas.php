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

// Ambil data tugas
$sql_tugas = "SELECT * FROM tugas WHERE id = $tugas_id AND user_id = $user_id";
$result_tugas = mysqli_query($db, $sql_tugas);
$tugas = mysqli_fetch_assoc($result_tugas);

if (!$tugas) {
    header("Location: halaman.php");
    exit;
}

// Ambil data subtugas
$sql_subtugas = "SELECT * FROM subtugas WHERE tugas_id = $tugas_id";
$result_subtugas = mysqli_query($db, $sql_subtugas);

// Update tugas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update tugas
    if (isset($_POST['nama_tugas'])) {
        $nama_tugas = mysqli_real_escape_string($db, $_POST['nama_tugas']);
        $deskripsi = mysqli_real_escape_string($db, $_POST['deskripsi']);
        $tenggat = mysqli_real_escape_string($db, $_POST['tenggat']);

        $sql_update = "UPDATE tugas SET nama_tugas='$nama_tugas', deskripsi='$deskripsi', tenggat='$tenggat' WHERE id=$tugas_id AND user_id=$user_id";
        mysqli_query($db, $sql_update);
    }

    // Tambah subtugas baru
    if (!empty($_POST['nama_subtugas'])) {
        $nama_subtugas = mysqli_real_escape_string($db, $_POST['nama_subtugas']);
        $sql_insert_sub = "INSERT INTO subtugas (tugas_id, nama_subtugas, status) VALUES ($tugas_id, '$nama_subtugas', 'belum selesai')";
        mysqli_query($db, $sql_insert_sub);
    }

    // Ubah subtugas
    if (isset($_POST['subtugas_id']) && isset($_POST['nama_subtugas_edit'])) {
        $subtugas_id = $_POST['subtugas_id'];
        $nama_subtugas_edit = mysqli_real_escape_string($db, $_POST['nama_subtugas_edit']);
        $sql_update_sub = "UPDATE subtugas SET nama_subtugas='$nama_subtugas_edit' WHERE id=$subtugas_id AND tugas_id=$tugas_id";
        mysqli_query($db, $sql_update_sub);
    }

    // Hapus subtugas
    if (isset($_POST['hapus_subtugas_id'])) {
        $hapus_subtugas_id = $_POST['hapus_subtugas_id'];
        $sql_delete_sub = "DELETE FROM subtugas WHERE id=$hapus_subtugas_id AND tugas_id=$tugas_id";
        mysqli_query($db, $sql_delete_sub);
    }

    header("Location: halaman.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="gaya.css">
</head>
<body>

<div class="wadah">
    <h2>Edit Tugas</h2>
    <form action="edit_tugas.php?id=<?= $tugas_id ?>" method="POST">
        <label for="nama_tugas">Nama Tugas:</label>
        <input type="text" name="nama_tugas" value="<?= htmlspecialchars($tugas['nama_tugas']) ?>" required>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" required><?= htmlspecialchars($tugas['deskripsi']) ?></textarea>

        <label for="tenggat">Tenggat:</label>
        <input type="date" name="tenggat" value="<?= $tugas['tenggat'] ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <h3>Subtugas</h3>
    <ul>
        <?php while ($subtugas = mysqli_fetch_assoc($result_subtugas)): ?>
            <li>
                <?= htmlspecialchars($subtugas['nama_subtugas']); ?>
                <!-- Form untuk mengubah nama subtugas -->
                <form action="edit_tugas.php?id=<?= $tugas_id ?>" method="POST" style="display:inline;">
                    <input type="hidden" name="subtugas_id" value="<?= $subtugas['id'] ?>">
                    <input type="text" name="nama_subtugas_edit" value="<?= htmlspecialchars($subtugas['nama_subtugas']); ?>" required>
                    <button type="submit">Ubah</button>
                </form>

                <!-- Form untuk menghapus subtugas -->
                <form action="edit_tugas.php?id=<?= $tugas_id ?>" method="POST" style="display:inline;">
                    <input type="hidden" name="hapus_subtugas_id" value="<?= $subtugas['id'] ?>">
                    <button type="submit">Hapus</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>

    <h3>Tambah Subtugas Baru</h3>
    <form action="edit_tugas.php?id=<?= $tugas_id ?>" method="POST">
        <label for="nama_subtugas">Nama Subtugas:</label>
        <input type="text" name="nama_subtugas" required>
        <button type="submit">Tambah Subtugas</button>
    </form>
</div>

</body>
</html>

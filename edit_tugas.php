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

$sql_tugas = "SELECT * FROM tugas WHERE id = $tugas_id AND user_id = $user_id";
$result_tugas = mysqli_query($db, $sql_tugas);
$tugas = mysqli_fetch_assoc($result_tugas);

if (!$tugas) {
    header("Location: halaman.php");
    exit;
}

$sql_subtugas = "SELECT * FROM subtugas WHERE tugas_id = $tugas_id";
$result_subtugas = mysqli_query($db, $sql_subtugas);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama_tugas'])) {
        $nama_tugas = mysqli_real_escape_string($db, $_POST['nama_tugas']);
        $deskripsi = mysqli_real_escape_string($db, $_POST['deskripsi']);
        $tenggat = mysqli_real_escape_string($db, $_POST['tenggat']);

        $sql_update = "UPDATE tugas SET nama_tugas='$nama_tugas', deskripsi='$deskripsi', tenggat='$tenggat' WHERE id=$tugas_id AND user_id=$user_id";
        mysqli_query($db, $sql_update);
    }

    if (!empty($_POST['nama_subtugas'])) {
        $nama_subtugas = mysqli_real_escape_string($db, $_POST['nama_subtugas']);
        $sql_insert_sub = "INSERT INTO subtugas (tugas_id, nama_subtugas, status) VALUES ($tugas_id, '$nama_subtugas', 'belum selesai')";
        mysqli_query($db, $sql_insert_sub);
    }

    if (isset($_POST['subtugas_id']) && isset($_POST['nama_subtugas_edit'])) {
        $subtugas_id = $_POST['subtugas_id'];
        $nama_subtugas_edit = mysqli_real_escape_string($db, $_POST['nama_subtugas_edit']);
        $sql_update_sub = "UPDATE subtugas SET nama_subtugas='$nama_subtugas_edit' WHERE id=$subtugas_id AND tugas_id=$tugas_id";
        mysqli_query($db, $sql_update_sub);
    }

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
    <style>
        body {
            background: url('rawa.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .wadah {
            max-width: 500px;
            background-color: white;
            padding: 30px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
        }
        h2, h3 {
            text-align: center;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            padding: 0;
            list-style-type: none;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="wadah">
    <h2>Edit Tugas</h2>
    <form action="edit_tugas.php?id=<?= $tugas_id ?>" method="POST">
        <label for="nama_tugas">Nama Tugas:</label>
        <input type="text" name="nama_tugas" value="<?= htmlspecialchars($tugas['nama_tugas']) ?>" required>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" required><?= htmlspecialchars($tugas['deskripsi']) ?></textarea>

        <label for="tenggat">Tenggat Waktu:</label>
                    <input type="datetime-local" name="tenggat" id="tenggat" value="<?= htmlspecialchars($tugas['tenggat']) ?>" required>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                            let sekarang = new Date();
                            sekarang.setMinutes(sekarang.getMinutes() - sekarang.getTimezoneOffset()); 
                            document.getElementById("tenggat").min = sekarang.toISOString().slice(0, 16);
                            });
                        </script>

        <button type="submit">Simpan Perubahan</button>
    

    <h3>Subtugas</h3>
    <ul>
        <?php while ($subtugas = mysqli_fetch_assoc($result_subtugas)): ?>
            <li>
                <?= htmlspecialchars($subtugas['nama_subtugas']); ?>
                <form action="edit_tugas.php?id=<?= $tugas_id ?>" method="POST" style="display:inline;">
                    <input type="hidden" name="subtugas_id" value="<?= $subtugas['id'] ?>">
                    <input type="text" name="nama_subtugas_edit" value="<?= htmlspecialchars($subtugas['nama_subtugas']); ?>" required>
                    <button type="submit">Ubah</button>
                </form>

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
        <p>
            <button onclick="window.location.href='halaman.php'">Kembali</button>
        </p>
        </form>
    </form>
</div>

</body>
</html>


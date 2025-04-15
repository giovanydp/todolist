<?php
date_default_timezone_set("Asia/Jakarta");
session_start();
include("koneksi.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date("Y-m-d H:i:s");

// Hitung tugas terlambat
$sql_jumlah_terlambat = "SELECT COUNT(*) AS jumlah FROM tugas WHERE user_id = $user_id AND status = 2";
$result_jumlah_terlambat = mysqli_query($db, $sql_jumlah_terlambat);
$row_jumlah_terlambat = mysqli_fetch_assoc($result_jumlah_terlambat);
$jumlah_terlambat = $row_jumlah_terlambat['jumlah'];

// Cek tugas kadaluarsa dalam 1 jam
$one_hour_later = date("Y-m-d H:i:s", strtotime('+1 hour'));
$now = date("Y-m-d H:i:s");
$sql_kadaluarsa = "SELECT nama_tugas FROM tugas WHERE user_id = $user_id AND status = 0 AND tenggat BETWEEN '$now' AND '$one_hour_later'";
$result_kadaluarsa = mysqli_query($db, $sql_kadaluarsa);
$tugas_kadaluarsa = [];
while ($row = mysqli_fetch_assoc($result_kadaluarsa)) {
    $tugas_kadaluarsa[] = $row['nama_tugas'];
}

// Update status jadi terlambat jika tenggat sudah lewat
$sql_update_terlambat = "UPDATE tugas SET status = 2 WHERE status = 0 AND tenggat < '$now'";
mysqli_query($db, $sql_update_terlambat);

// Tugas diselesaikan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tugas_id'])) {
    $tugas_id = $_POST['tugas_id'];
    mysqli_query($db, "UPDATE tugas SET status = 1 WHERE id = $tugas_id AND user_id = $user_id");
}

// Subtugas diperbarui
if (isset($_GET['subtugas_id'])) {
    $subtugas_id = $_GET['subtugas_id'];
    $status = $_GET['status'] == '1' ? 'selesai' : 'belum';
    mysqli_query($db, "UPDATE subtugas SET status = '$status' WHERE id = $subtugas_id 
        AND tugas_id IN (SELECT id FROM tugas WHERE user_id = $user_id)");
}

// Ambil keyword pencarian
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($db, $_GET['cari']) : '';

// Query tugas aktif
if ($cari) {
    $sql_tugas = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 0 AND nama_tugas = '$cari' ORDER BY tenggat ASC";
} else {
    $sql_tugas = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 0 ORDER BY tenggat ASC";
}
$result_tugas = mysqli_query($db, $sql_tugas);

// Query tugas terlambat
if ($cari) {
    $sql_terlambat = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 2 AND nama_tugas = '$cari' ORDER BY tenggat ASC";
} else {
    $sql_terlambat = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 2 ORDER BY tenggat ASC";
}
$result_terlambat = mysqli_query($db, $sql_terlambat);

// Query tugas selesai
if ($cari) {
    $sql_selesai = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 1 AND nama_tugas = '$cari' ORDER BY tenggat ASC";
} else {
    $sql_selesai = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 1 ORDER BY tenggat ASC";
}
$result_selesai = mysqli_query($db, $sql_selesai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="styles1.css">
    <script>
        function updateSubtugas(subtugas_id, checkbox, tugas_id) {
            var status = checkbox.checked ? '1' : '0';
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "proses_selesaikan_subtugas.php?subtugas_id=" + subtugas_id + "&status=" + status + "&tugas_id=" + tugas_id, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (checkbox.checked) {
                        checkbox.disabled = true;
                    }
                    if (xhr.responseText.trim() === 'completed') {
                        document.getElementById('tugas-' + tugas_id).submit();
                    }
                }
            };
            xhr.send();
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var jumlahTerlambat = <?= $jumlah_terlambat ?>;
            var tugasKadaluarsa = <?= json_encode($tugas_kadaluarsa) ?>;

            if (jumlahTerlambat > 0) {
                alert("⚠️ Ada " + jumlahTerlambat + " tugas yang terlambat!");
            }
            if (tugasKadaluarsa.length > 0) {
                tugasKadaluarsa.forEach(function (tugas) {
                    alert("⏳ Tugas '" + tugas + "' akan kadaluarsa kurang dari 1 jam!");
                });
            }
        });
    </script>
</head>
<body>

<div class="wadah">
    <h2>Daftar Tugas</h2>
    <a href="form_tambah.php" class="tombol-tambah">Tambah Tugas</a>

    <!-- Form Cari -->
    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="cari" placeholder="Cari tugas..." value="<?= htmlspecialchars($cari) ?>">
        <button type="submit" class="tombol-tambah">Cari</button>
    </form>

    <?php if ($cari): ?>
        <p>Menampilkan hasil untuk pencarian: <strong><?= htmlspecialchars($cari) ?></strong></p>
    <?php endif; ?>

    <?php
        $jumlah_semua_tugas = mysqli_num_rows($result_tugas) + mysqli_num_rows($result_selesai) + mysqli_num_rows($result_terlambat);
        if ($cari && $jumlah_semua_tugas === 0): ?>
        <p><strong>Tugas tidak ditemukan.</strong></p>
    <?php endif; ?>

    <h3>Tugas Aktif</h3>
    <div class="daftar-tugas">
        <?php while ($row = mysqli_fetch_assoc($result_tugas)): ?>
            <form method="POST" class="tugas" id="tugas-<?= $row['id']; ?>">
                <input type="hidden" name="tugas_id" value="<?= $row['id']; ?>">
                <input type="checkbox" name="status" onchange="this.form.submit()">
                <div class="info-tugas">
                    <h3><?= htmlspecialchars($row['nama_tugas']); ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                    <span class="tenggat">Tenggat: <?= htmlspecialchars($row['tenggat']); ?></span>
                </div>
                <a href="edit_tugas.php?id=<?= $row['id'] ?>" class="tombol-tambah">Edit</a>
                <a href="hapus_tugas.php?id=<?= $row['id'] ?>" class="tombol-tambah" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">Hapus</a>
                <ul>
                    <?php
                    $tugas_id = $row['id'];
                    $sql_subtugas = "SELECT * FROM subtugas WHERE tugas_id = $tugas_id";
                    $result_subtugas = mysqli_query($db, $sql_subtugas);
                    while ($subtugas = mysqli_fetch_assoc($result_subtugas)):
                    ?>
                        <li>
                            <input type="checkbox" onchange="updateSubtugas(<?= $subtugas['id'] ?>, this, <?= $tugas_id ?>)" <?= $subtugas['status'] == 'selesai' ? 'checked disabled' : '' ?>>
                            <?= htmlspecialchars($subtugas['nama_subtugas']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </form>
        <?php endwhile; ?>
    </div>

    <h3>Tugas Selesai</h3>
    <div class="daftar-tugas selesai">
        <?php while ($row = mysqli_fetch_assoc($result_selesai)): ?>
            <div class="tugas selesai">
                <input type="checkbox" checked disabled>
                <div class="info-tugas">
                    <h3><?= htmlspecialchars($row['nama_tugas']); ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                    <span class="tenggat">Tenggat: <?= htmlspecialchars($row['tenggat']); ?></span>
                </div>
                <a href="hapus_tugas.php?id=<?= $row['id'] ?>" class="tombol-tambah" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">Hapus</a>
                <ul>
                    <?php
                    $tugas_id = $row['id'];
                    $sql_subtugas = "SELECT * FROM subtugas WHERE tugas_id = $tugas_id";
                    $result_subtugas = mysqli_query($db, $sql_subtugas);
                    while ($subtugas = mysqli_fetch_assoc($result_subtugas)):
                    ?>
                        <li>
                            <input type="checkbox" checked disabled>
                            <?= htmlspecialchars($subtugas['nama_subtugas']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endwhile; ?>
    </div>

    <h3>Tugas Terlambat</h3>
    <div class="daftar-tugas terlambat">
        <?php while ($row = mysqli_fetch_assoc($result_terlambat)): ?>
            <form method="POST" class="tugas terlambat">
                <input type="hidden" name="tugas_id" value="<?= $row['id']; ?>">
                <input type="checkbox" disabled name="status">
                <div class="info-tugas">
                    <h3><?= htmlspecialchars($row['nama_tugas']); ?></h3>
                    <p><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                    <span class="tenggat">Tenggat: <?= htmlspecialchars($row['tenggat']); ?></span>
                </div>
                <a href="hapus_tugas.php?id=<?= $row['id'] ?>" class="tombol-tambah" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">Hapus</a>
                <ul>
                    <?php
                    $tugas_id = $row['id'];
                    $sql_subtugas = "SELECT * FROM subtugas WHERE tugas_id = $tugas_id";
                    $result_subtugas = mysqli_query($db, $sql_subtugas);
                    while ($subtugas = mysqli_fetch_assoc($result_subtugas)):
                    ?>
                        <li>
                            <input type="checkbox" disabled>
                            <?= htmlspecialchars($subtugas['nama_subtugas']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </form>
        <?php endwhile; ?>
    </div>

    <form action="logout.php" method="POST">
        <button type="submit" class="tombol-tambah">Logout</button>
    </form>
</div>

</body>
</html>
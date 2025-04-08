<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="gaya.css">
</head>
<body>
    <div class="wadah">
        <h2>Tambah Tugas</h2>
        <form action="proses_tambah.php" method="POST">
            <p>
                <label for="nama_tugas">Nama Tugas:</label>
                <input type="text" name="nama_tugas" required>
            </p>
            <p>
                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" required></textarea>
            </p>
            <p>
                <label for="tenggat">Tenggat Waktu:</label>
                    <input type="datetime-local" name="tenggat" id="tenggat" required>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                            let sekarang = new Date();
                            sekarang.setMinutes(sekarang.getMinutes() - sekarang.getTimezoneOffset()); 
                            document.getElementById("tenggat").min = sekarang.toISOString().slice(0, 16);
                            });
                        </script>
            </p>

            <h4>Subtugas:</h4>
            <div id="subtugas-container">
                <div class="subtugas-item">
                    <input type="text" name="subtugas[]" placeholder="Nama subtugas" required>
                </div>
            </div>
            <button id="tambah-subtugas">Tambah Subtugas</button>
            <p><button name="tambah_tugas">Tambah Tugas</button></p>
            <p>
                <button onclick="window.location.href='halaman.php'">Kembali</button>
            </p>
        </form>
    </div>

    <script>
        document.getElementById('tambah-subtugas').addEventListener('click', function() {
            var container = document.getElementById("subtugas-container");
            var input = document.createElement("input");
            input.type = "text";
            input.name = "subtugas[]";
            input.placeholder = "Nama subtugas";
            container.appendChild(input);
        });
    </script>
</body>
</html>

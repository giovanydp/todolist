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
                <label for="tenggat">Tenggat:</label>
                <input type="datetime-local" name="tenggat" required>
            </p>

            <h4>Subtugas:</h4>
            <div id="subtugas-container">
                <div class="subtugas-item">
                    <input type="text" name="subtugas[]" placeholder="Nama subtugas" required>
                </div>
            </div>
            <button type="button" id="tambah-subtugas">Tambah Subtugas</button>
            <p><button type="submit" name="tambah_tugas">Tambah Tugas</button></p>
        </form>
    </div>

    <script>
        // Menambahkan input untuk subtugas
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

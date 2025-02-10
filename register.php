<?php
include("koneksi.php");

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql_check = "SELECT id FROM users WHERE username = ?";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error_message = "Username telah dipakai. Silakan pilih username lain.";
    } else {
        $sql_insert = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt_insert = $db->prepare($sql_insert);
        $stmt_insert->bind_param("ss", $username, $password);
        if ($stmt_insert->execute()) {
            header("Location: login.php?status=register_sukses");
            exit;
        } else {
            $error_message = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: url('rawa.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="wadah">
        
        
        <?php if (!empty($error_message)) : ?>
            <p class="peringatan"><?= $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
        <h2>Daftar Akun</h2>
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>

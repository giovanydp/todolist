<?php
include("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        echo "<script>alert('❌ Username hanya boleh huruf dan angka!'); window.location='register.php';</script>";
        exit;
    }

    if (strlen($password) < 8) {
        echo "<script>alert('❌ Password harus minimal 8 karakter!'); window.location='register.php';</script>";
        exit;
    }
    
    // Periksa apakah username sudah ada di database
    $query_check = "SELECT * FROM users WHERE username = '$username'";
    $result_check = mysqli_query($db, $query_check);
    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('❌ Username telah terdaftar! Silakan pilih username lain.'); window.location='register.php';</script>";
        exit;
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password_hashed')";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('✅ Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal mendaftar!'); window.location='register.php';</script>";
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
        button, .register-button {
            display: inline-block;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        .register-button {
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="regis">
        
        
        <?php if (!empty($error_message)) : ?>
            <p class="peringatan"><?= $error_message; ?></p>
        <?php endif; ?>
 
        <form method="POST" action="register.php" onsubmit="return validateForm()">
        <h2>Daftar Akun</h2>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Register</button>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <a href="login.php" class="register-button">kembali</a>
        </form>
    </div>
</body>
</html>

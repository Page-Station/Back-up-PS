<?php
include 'database.php'; // Menghubungkan ke database

session_start(); // Memulai session untuk mengelola status login

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>
    <img src="../image/pagestationblue.png" alt="logo page station" class="logo">
    <div class="container">
        <h1>Profil Pengguna</h1>
        <p><strong>Username:</strong> <?php echo $_SESSION['user']['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['user']['email']; ?></p>

        <!-- Menampilkan daftar buku yang dipinjam -->
        <h2>Buku yang Dipinjam</h2>
        <!-- Tombol logout -->
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>

</html>

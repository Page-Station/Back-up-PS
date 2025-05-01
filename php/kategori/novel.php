<?php
session_start();
include('../database.php');


// Fetch books in the "Pelajaran" category
$query_books = "SELECT * FROM books WHERE category = 'Pelajaran'";
$result_books = $conn->query($query_books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Station</title>
    <link rel="stylesheet" href="../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/script.js" defer>
        const toggleButton = document.querySelector('.mode-toggle');
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</head>
<body>
    <!-- Icons di Pojok Kanan Atas -->
    <div class="top-icons">
        <i class="fas fa-moon mode-toggle"></i>
        <a href="../profile.php"><i class="fas fa-user-circle profile-icon"></i></a>
    </div>
    
    <!-- Sidebar -->
    <input type="checkbox" id="check">
    <label for="check">
        <i class="fas fa-bars" id="btn"></i>
        <i class="fas fa-times" id="cancel"></i>
    </label>
    <div class="sidebar">
        <img src="image/pagestationblue.png" alt="Logo">
        <ul>
            <li><a href="#"><i class="fas fa-qrcode"></i>Home</a></li>
            <li><a href="#"><i class="far fa-question-circle"></i>About us</a></li>
            <li><a href="#"><i class="far fa-envelope"></i>Contact us</a></li>
            <li><a href="#"><i class="fas fa-calendar-week"></i>Rak pinjam</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <section class="content">
        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" placeholder="Cari sesuatu yang keren">
            <i class="fas fa-search"></i>
        </div>

        <!-- White Boxes -->
        <div class="white-boxes">
            <div class="kategori">
                <h3>Novel</h3>
            <div class="category">
                <a href="novel/horror.php">Horor</a>
            </div>
            </div>
        </div>
    </section>
    <script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert('Klik kanan dinonaktifkan.');
    });
    </script>
</body>
</html>

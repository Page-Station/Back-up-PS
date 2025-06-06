<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novel - Page Station</title>
    <link rel="stylesheet" href="../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .subkategori-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }

        .subkategori-box {
            width: 140px;
            height: 120px;
            border: 2px solid #337ab7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            background-color: white;
            transition: 0.3s;
            text-decoration: none;
            color: black;
        }

        .subkategori-box:hover {
            background-color: #e6f0ff;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Top Icons -->
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
    <img src="../../image/pagestationblue.png" alt="Logo">
    <ul>
        <li><a href="../../index.php"><i class="fas fa-qrcode"></i>Home</a></li>
        <li><a href="../../learn-more.html"><i class="far fa-question-circle"></i>About us</a></li>
        <li><a href="../../learn-more.html"><i class="far fa-envelope"></i>Contact us</a></li>
        <li><a href="../rak-pinjam.php"><i class="fas fa-calendar-week"></i>Rak pinjam</a></li>
    </ul>
</div>

<!-- Main Content -->
<section class="content">

    <div class="white-boxes">
        <div class="kategori">
            <h3>Novel</h3>
            <div class="subkategori-container">
                <a href="novel/romance.php" class="subkategori-box">Romance</a>
                <a href="novel/horror.php" class="subkategori-box">Horror</a>
                <a href="novel/comedy.php" class="subkategori-box">Comedy</a>
                <a href="novel/fantasy.php" class="subkategori-box">Fantasy</a>
                <a href="novel/mystery.php" class="subkategori-box">Mystery</a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    alert('Klik kanan dinonaktifkan.');
});

document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector('.mode-toggle');
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
        });
    }
});
</script>

</body>
</html>

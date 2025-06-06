<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psikologi - Page Station</title>
    <link rel="stylesheet" href="../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
      
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
            <h3>Psikologi</h3>
            <div class="subkategori-container">
                <a href="psikologi/psikologi-agama.php" class="subkategori-box">Psikologi Agama</a>
                <a href="psikologi/psikolgi-sosial.php" class="subkategori-box">Psikologi Sosial</a>
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

<?php
session_start();
include('../database.php');



$jenjang = $_GET['jenjang'] ?? null;
$kelas = $_GET['kelas'] ?? null;

if (!$jenjang || !$kelas) {
    echo "Parameter jenjang dan kelas tidak lengkap.";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM books WHERE category = 'Pelajaran' AND jenjang = ? AND kelas = ?");
$stmt->bind_param("si", $jenjang, $kelas);
$stmt->execute();
$result_books = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars("Kelas $kelas - $jenjang"); ?> - Page Station</title>
    <link rel="stylesheet" href="../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <script defer>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.mode-toggle');
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
        });
    });
    </script>
</head>
<body>
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
        <div class="search-bar">
            <input type="text" placeholder="Cari sesuatu yang keren">
            <i class="fas fa-search"></i>
        </div>

        <div class="white-boxes">
        <div class="kategori">
            <h3><?php echo htmlspecialchars("Pelajaran - $jenjang Kelas $kelas"); ?></h3>
            <div class="book-grid">
                <?php if ($result_books->num_rows > 0): ?>
                    <?php while ($book = $result_books->fetch_assoc()): ?>
                        <a href="detail-buku.php?id=<?php echo $book['id']; ?>" class="book-item">
                            <?php if (!empty($book['cover_image'])): ?>
                                <img src="../<?php echo $book['cover_image']; ?>" alt="Cover Buku" class="book-cover">
                            <?php else: ?>
                                <div class="book-cover" style="background-color:#eee; display:flex; align-items:center; justify-content:center;">Tidak ada cover</div>
                            <?php endif; ?>
                            <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada buku untuk jenjang dan kelas ini.</p>
                <?php endif; ?>
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

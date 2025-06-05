<?php
session_start();
include('../../database.php');

// Nama subkategori yang ingin ditampilkan di halaman ini
$sub_category = 'Romance';

// Ambil buku dari database yang kategori = Novel dan sub_category = Romance
$stmt = $conn->prepare("SELECT * FROM books WHERE category = 'Novel' AND sub_category = ?");
$stmt->bind_param("s", $sub_category);
$stmt->execute();
$result_books = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Novel - <?php echo $sub_category; ?> | Page Station</title>
    <link rel="stylesheet" href="../../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script defer>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.querySelector('.mode-toggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
            });
        }
    });
    </script>
</head>
<body>

<!-- Top Icons -->
<div class="top-icons">
    <i class="fas fa-moon mode-toggle"></i>
    <a href="../../profile.php"><i class="fas fa-user-circle profile-icon"></i></a>
</div>

<!-- Sidebar -->
<input type="checkbox" id="check">
<label for="check">
    <i class="fas fa-bars" id="btn"></i>
    <i class="fas fa-times" id="cancel"></i>
</label>
<div class="sidebar">
    <img src="../../../image/pagestationblue.png" alt="Logo">
    <ul>
        <li><a href="../../../index.php"><i class="fas fa-qrcode"></i>Home</a></li>
        <li><a href="../../../learn-more.html"><i class="far fa-question-circle"></i>About us</a></li>
        <li><a href="../../../learn-more.html"><i class="far fa-envelope"></i>Contact us</a></li>
        <li><a href="../../rak-pinjam.php"><i class="fas fa-calendar-week"></i>Rak pinjam</a></li>
    </ul>
</div>

<!-- Main Content -->
<section class="content">
    <div class="search-bar">
        <input type="text" placeholder="Cari novel Romance...">
        <i class="fas fa-search"></i>
    </div>

    <div class="white-boxes">
        <div class="kategori">
            <h3>Novel - <?php echo $sub_category; ?></h3>
            <div class="book-grid">
                <?php if ($result_books->num_rows > 0): ?>
                    <?php while ($book = $result_books->fetch_assoc()): ?>
                        <div class="book-item">
                            <a href="../detail-buku.php?id=<?php echo $book['id']; ?>">
                                <?php if (!empty($book['cover_image'])): ?>
                                    <img src="../../admin/uploads/covers/<?php echo $book['cover_image']; ?>" alt="Cover Buku" class="book-cover">
                                <?php else: ?>
                                    <div class="book-cover" style="background-color:#eee; display:flex; align-items:center; justify-content:center;">Tidak ada cover</div>
                                <?php endif; ?>
                                <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                            </a>
                            <p>Stok: <?php echo $book['stock']; ?></p>
                            <?php if ($book['stock'] > 0): ?>
                                <form action="../../pinjam-buku.php" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit">Pinjam</button>
                                </form>
                            <?php else: ?>
                                <p style="color:red;">Stok habis</p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada buku romance tersedia saat ini.</p>
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

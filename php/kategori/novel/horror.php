<?php
session_start();
include('../../database.php');

// Tambahan: fitur search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sub-kategori yang ingin ditampilkan
$sub_category = 'Horror';

// Tambahan: Query pencarian jika ada search
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE category = 'Novel' AND sub_category = ? AND title LIKE ?");
    $search_param = '%' . $search . '%';
    $stmt->bind_param("ss", $sub_category, $search_param);
    $stmt->execute();
    $result_books = $stmt->get_result();
} else {
    // Query default tanpa pencarian
    $stmt = $conn->prepare("SELECT * FROM books WHERE category = 'Novel' AND sub_category = ?");
    $stmt->bind_param("s", $sub_category);
    $stmt->execute();
    $result_books = $stmt->get_result();
}
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
    <!-- Tambahan: Form search -->
    <div class="search-bar">
        <form method="GET" action="horror.php">
            <input type="text" name="search" placeholder="Cari Buku Novel Horror..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
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
                            <?php if ($book['stock'] > 0): ?>
                            <?php else: ?>
                                <p style="color:red;">Stok habis</p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada buku <?php echo $sub_category; ?> ditemukan<?php if ($search) echo " untuk '<strong>" . htmlspecialchars($search) . "</strong>'"; ?>.</p>
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

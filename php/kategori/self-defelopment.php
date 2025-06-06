<?php
session_start();
include('../database.php');

// Tangkap pencarian jika ada
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Siapkan query
if (!empty($search)) {
    // Gunakan prepared statement agar aman dari SQL Injection
    $query_books = "SELECT * FROM books WHERE TRIM(LOWER(category)) = 'self development' AND title LIKE ?";
    $stmt = $conn->prepare($query_books);
    $param = '%' . $search . '%';
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result_books = $stmt->get_result();
} else {
    // Jika tidak ada pencarian, tampilkan semua Self Development
    $query_books = "SELECT * FROM books WHERE TRIM(LOWER(category)) = 'self development'";
    $result_books = $conn->query($query_books);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Self Development - Page Station</title>
    <link rel="stylesheet" href="../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    <img src="../../image/pagestationblue.png" alt="Logo">
    <ul>
        <li><a href="../../index.php"><i class="fas fa-qrcode"></i>Home</a></li>
        <li><a href="../../learn-more.html"><i class="far fa-question-circle"></i>About us</a></li>
        <li><a href="../../learn-more.html"><i class="far fa-envelope"></i>Contact us</a></li>
        <li><a href="../rak-pinjam.php"><i class="fas fa-calendar-week"></i>Rak pinjam</a></li>
    </ul>
</div>

<!-- Search Bar -->
<section class="content">
    <div class="search-bar">
        <form method="GET" action="self-defelopment.php">
            <input type="text" name="search" placeholder="Cari buku Self Development..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="white-boxes">
        <div class="kategori">
            <section class="content">
                <div class="book-grid">
                    <?php if ($result_books->num_rows > 0): ?>
                        <?php while ($book = $result_books->fetch_assoc()): ?>
                            <div class="book-item">
                                <a href="detail-buku.php?id=<?php echo $book['id']; ?>">
                                    <?php if (!empty($book['cover_image'])): ?>
                                        <img src="../admin/uploads/covers/<?php echo $book['cover_image']; ?>" alt="book-cover" class="book-cover">
                                    <?php else: ?>
                                        <div class="book-cover">Tidak ada cover</div>
                                    <?php endif; ?>
                                    <h4 class="judul"><?php echo htmlspecialchars($book['title']); ?></h4>
                                </a>
                                <?php if ($book['stock'] > 0): ?>
                                <?php else: ?>
                                    <p style="color:red;">Stok habis</p>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Tidak ada buku ditemukan<?php if ($search) echo " untuk kata kunci '<strong>" . htmlspecialchars($search) . "</strong>'"; ?>.</p>
                    <?php endif; ?>
                </div>
            </section>
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

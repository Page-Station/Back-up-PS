<?php
session_start();
include('../database.php');

// Tangkap pencarian jika ada
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query berdasarkan pencarian
if (!empty($search)) {
    $query_books = "SELECT * FROM books WHERE TRIM(LOWER(category)) = 'dongeng' AND title LIKE ?";
    $stmt = $conn->prepare($query_books);
    $param = '%' . $search . '%';
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result_books = $stmt->get_result();
} else {
    $query_books = "SELECT * FROM books WHERE TRIM(LOWER(category)) = 'dongeng'";
    $result_books = $conn->query($query_books);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dongeng - Page Station</title>
    <link rel="stylesheet" href="../../css/kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="dongeng.php">
            <input type="text" name="search" placeholder="Cari buku dongeng..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- White Boxes -->
    <div class="white-boxes">
        <div class="kategori">
            <h3>Dongeng</h3>
            <div class="category-container">
                <?php if ($result_books->num_rows > 0): ?>
                    <?php while ($book = $result_books->fetch_assoc()): ?>
                        <div class="book-item">
                            <h4><?php echo $book['title']; ?></h4>
                            <p>Penulis: <?php echo $book['author']; ?></p>
                            <p><?php echo substr($book['description'], 0, 100) . '...'; ?></p>
                            <div class="book-actions">
                                <a href="<?php echo $book['pdf_path']; ?>" target="_blank" class="btn read-btn">Baca Buku</a>
                                <button class="btn borrow-btn" onclick="alert('Buku berhasil dipinjam!')">Pinjam</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada buku ditemukan<?php if ($search) echo " untuk kata kunci '<strong>" . htmlspecialchars($search) . "</strong>'"; ?>.</p>
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

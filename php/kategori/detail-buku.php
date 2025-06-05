<?php
session_start();
include('../database.php');

if (!isset($_GET['id'])) {
    echo "ID buku tidak ditemukan.";
    exit;
}

$book_id = intval($_GET['id']);
$query = $conn->prepare("SELECT * FROM books WHERE id = ?");
$query->bind_param("i", $book_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    echo "Buku tidak ditemukan.";
    exit;
}

$book = $result->fetch_assoc();
$user_logged_in = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku - <?= htmlspecialchars($book['title']) ?></title>
    <link rel="stylesheet" href="../../css/detail-buku.css">
</head>
<body>

<a href="javascript:history.back()" class="back-btn">← Kembali</a>

<div class="detail-container">
    <!-- Cover Buku -->
    <div class="detail-cover">
        <?php if (!empty($book['cover_image'])): ?>
            <img src="../admin/uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover Buku">
        <?php else: ?>
            <div style="width:250px; height:350px; background:#eee; display:flex; align-items:center; justify-content:center;">Tidak ada cover</div>
        <?php endif; ?>
    </div>

    <!-- Informasi Buku -->
    <div class="detail-info">
        <h1><?= htmlspecialchars($book['title']) ?></h1>
        <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
        <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($book['description'])) ?></p>
        <p><strong>Kategori:</strong> <?= htmlspecialchars($book['category']) ?></p>
        <?php if (!empty($book['sub_category'])): ?>
            <p><strong>Sub Kategori:</strong> <?= htmlspecialchars($book['sub_category']) ?></p>
        <?php endif; ?>
        <p><strong>Stok Tersedia:</strong> <?= intval($book['stock']) ?> buku</p>

        <?php if ($user_logged_in): ?>
            <?php if ($book['stock'] > 0): ?>
                <a href="pinjam-buku.php?book_id=<?= $book['id'] ?>" class="btn read-btn">Pinjam & Baca Buku</a>
            <?php else: ?>
                <p style="color: red;"><strong>Stok habis. Tidak dapat dipinjam saat ini.</strong></p>
            <?php endif; ?>
        <?php else: ?>
            <p><a href="../php/login.php" class="btn">Login untuk membaca</a></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

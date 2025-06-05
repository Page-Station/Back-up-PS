<?php
include('database.php');

if (!isset($_GET['id'])) {
    echo "ID buku tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']); // sanitasi input

$query = "SELECT * FROM books WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Buku tidak ditemukan.";
    exit;
}

$books = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku - <?= htmlspecialchars($books['title']) ?></title>
    <link rel="stylesheet" href="../css/kategori.css">
</head>
<body>
    <div class="detail-container">
        <h1><?= htmlspecialchars($books['title']) ?></h1>
        <p><strong>Penulis:</strong> <?= htmlspecialchars($books['author']) ?></p>
        <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($books['description'])) ?></p>
        <?php if (!empty($books['cover_image'])): ?>
            <img src="uploads/<?= htmlspecialchars($books['cover_image']) ?>" alt="Cover Buku" style="max-width:200px;">
        <?php endif; ?>

        <a href="index.php">← Kembali ke Beranda</a>
    </div>
</body>
</html>

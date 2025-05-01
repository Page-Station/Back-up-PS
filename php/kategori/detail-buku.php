<?php
session_start();
include('../database.php');

// Cek ID buku
if (!isset($_GET['id'])) {
    header('Location: pelajaran.php');
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM books WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Buku tidak ditemukan.";
    exit();
}

$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - <?php echo htmlspecialchars($book['title']); ?></title>
    <link rel="stylesheet" href="../../css/detail-buku.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
       
    </style>
</head>
<body>

    <a href="self-defelopment.php" class="back-btn">← Kembali</a>

    <div class="detail-container">
        <!-- Cover -->
        <div class="detail-cover">
            <?php if (!empty($book['cover_image'])): ?>
                <img src="../<?php echo $book['cover_image']; ?>" alt="Cover Buku">
            <?php else: ?>
                <div style="width:250px; height:350px; background:#eee; display:flex; align-items:center; justify-content:center;">Tidak ada cover</div>
            <?php endif; ?>
        </div>

        <!-- Info Buku -->
        <div class="detail-info">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <p><strong>Penulis:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Deskripsi:</strong> <?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            <p><strong>Diterbitkan:</strong> <?php echo htmlspecialchars($book['published_date'] ?? 'Tidak diketahui'); ?></p>
            <a href="../<?php echo $book['pdf_path']; ?>" target="_blank" class="btn read-btn">Baca Buku</a>
        </div>
    </div>

</body>
</html>

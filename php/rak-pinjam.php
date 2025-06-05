<?php
session_start();
include('database.php');

// Cek login
if (!isset($_SESSION['user'])) {
    echo "Silakan login terlebih dahulu.";
    exit();
}

$user_id = $_SESSION['user']['id'];

// Ambil daftar pinjaman aktif user
$query = "SELECT borrowings.id AS borrow_id, books.id AS book_id, books.title, books.author, books.cover_image, borrowings.progress 
          FROM borrowings 
          JOIN books ON borrowings.book_id = books.id 
          WHERE borrowings.user_id = ? AND borrowings.return_date IS NULL";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rak Pinjam Saya</title>
    <link rel="stylesheet" href="../css/rak-pinjam.css">
    <style>
        .borrow-list {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        .borrow-item {
            border: 1px solid #ccc;
            padding: 16px;
            width: 240px;
            border-radius: 6px;
            background: #f9f9f9;
        }
        .borrow-item img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 4px;
        }
        .progress-container {
            width: 100%;
            background: #eee;
            height: 10px;
            border-radius: 5px;
            margin: 8px 0;
        }
        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            border-radius: 5px;
        }
        form {
            margin-top: 8px;
        }
        .no-borrows {
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Rak Pinjam Saya</h1>

<?php if ($result->num_rows > 0): ?>
    <div class="borrow-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="borrow-item">
                <!-- Cover -->
                <?php if (!empty($row['cover_image'])): ?>
                    <img src="admin/uploads/covers/<?php echo htmlspecialchars($row['cover_image']); ?>" alt="Cover Buku">
                <?php else: ?>
                    <div class="no-cover" style="width:100%;height:320px;background:#ddd;text-align:center;line-height:320px;">Tidak ada cover</div>
                <?php endif; ?>

                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p class="author">Penulis: <?php echo htmlspecialchars($row['author']); ?></p>

                <!-- Progress -->
                <div class="progress-container" title="Progress membaca: <?php echo $row['progress']; ?>%">
                    <div class="progress-bar" style="width: <?php echo intval($row['progress']); ?>%;"></div>
                </div>

                <!-- Update progress -->
                <form action="update-progress.php" method="POST">
                    <input type="hidden" name="borrow_id" value="<?php echo $row['borrow_id']; ?>">
                    <input type="number" name="progress" min="0" max="100" value="<?php echo intval($row['progress']); ?>" required>
                    <button type="submit">Update Progress</button>
                </form>

                <!-- Kembalikan Buku -->
                <form action="kembalikan-buku.php" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?');">
                    <input type="hidden" name="borrow_id" value="<?php echo $row['borrow_id']; ?>">
                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                    <button type="submit">Kembalikan Buku</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="no-borrows">Anda belum meminjam buku apapun.</p>
<?php endif; ?>

</body>
</html>

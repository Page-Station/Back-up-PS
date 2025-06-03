<?php
session_start();
include('database.php');

if (!isset($_SESSION['user'])) {
    echo "Silakan login terlebih dahulu.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id'];

    // Update return_date di borrowings
    $stmt = $conn->prepare("UPDATE borrowings SET return_date = NOW() WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $borrow_id, $_SESSION['user']['id']);
    $stmt->execute();

    // Tambah stok buku
    $stmt = $conn->prepare("UPDATE books SET stock = stock + 1 WHERE id = ?");
    $stmt->bind_param('i', $book_id);
    $stmt->execute();

    header("Location: rak-pinjam.php");
    exit();
} else {
    echo "Akses tidak valid.";
}
?>

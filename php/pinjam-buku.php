<?php
session_start();
include('database.php');

if (!isset($_SESSION['user'])) {
    echo "Silakan login terlebih dahulu.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $book_id = $_POST['book_id'];

    // Cek stok buku
    $stmt = $conn->prepare("SELECT stock FROM books WHERE id = ?");
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "Buku tidak ditemukan.";
        exit();
    }

    if ($book['stock'] > 0) {
        // Kurangi stok buku
        $stmt = $conn->prepare("UPDATE books SET stock = stock - 1 WHERE id = ?");
        $stmt->bind_param('i', $book_id);
        $stmt->execute();

        // Tambah data peminjaman
        $stmt = $conn->prepare("INSERT INTO borrowings (user_id, book_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $user_id, $book_id);
        $stmt->execute();

        // Redirect ke halaman rak pinjam
        header("Location: rak-pinjam.php");
        exit();
    } else {
        echo "Stok buku habis, tidak bisa meminjam.";
    }
} else {
    echo "Akses tidak valid.";
}
?>

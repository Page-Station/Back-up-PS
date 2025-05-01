<?php
session_start();
include('database.php');

if (!isset($_SESSION['user'])) {
    echo "Silakan login terlebih dahulu.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $progress = intval($_POST['progress']);
    if ($progress < 0) $progress = 0;
    if ($progress > 100) $progress = 100;

    $stmt = $conn->prepare("UPDATE borrowings SET progress = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param('iii', $progress, $borrow_id, $_SESSION['user']['id']);
    $stmt->execute();

    header("Location: rak-pinjam.php");
    exit();
} else {
    echo "Akses tidak valid.";
}
?>

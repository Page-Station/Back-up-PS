<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['field']) && isset($_POST['value'])) {
        $field = $_POST['field'];
        $value = trim($_POST['value']);

        // Validasi field yang diizinkan
        $allowedFields = ['fullname', 'phone'];
        if (in_array($field, $allowedFields)) {
            $stmt = $conn->prepare("UPDATE users SET $field = ? WHERE id = ?");
            $stmt->bind_param("si", $value, $userId);
            $stmt->execute();

            // Perbarui sesi agar perubahan langsung terlihat
            $_SESSION['user'][$field] = $value;
        }
    }
}

header("Location: profile.php");
exit();
?>

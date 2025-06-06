<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];
    $filename = basename($file['name']);
    $target_dir = "uploads/profile/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . time() . '_' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Simpan path ke database
        $stmt = $conn->prepare("UPDATE users SET profile_photo = ? WHERE id = ?");
        $stmt->bind_param("si", $target_file, $user_id);
        $stmt->execute();

        // Update session
        $_SESSION['user']['profile_photo'] = $target_file;
    }
}

header("Location: profile.php");
exit();

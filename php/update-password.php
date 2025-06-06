<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Ambil password lama dari database
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

// Verifikasi password lama
if (!password_verify($old_password, $hashed_password)) {
    echo "<script>alert('Password lama salah!'); window.history.back();</script>";
    exit();
}

// Cek kesesuaian password baru
if ($new_password !== $confirm_password) {
    echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
    exit();
}

// Hash dan update password baru
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $new_hashed, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Kata sandi berhasil diubah.'); window.location.href='profile.php';</script>";
} else {
    echo "<script>alert('Gagal mengubah kata sandi.'); window.history.back();</script>";
}
?>

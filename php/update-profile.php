<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$birthdate = $_POST['birthdate'];
$phone = $_POST['phone'];

// Update database
$stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, gender = ?, birthdate = ?, phone = ? WHERE id = ?");
$stmt->bind_param("sssssi", $fullname, $email, $gender, $birthdate, $phone, $user_id);
$stmt->execute();

// Update session agar profil terbaru langsung tampil
$_SESSION['user']['fullname'] = $fullname;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['gender'] = $gender;
$_SESSION['user']['birthdate'] = $birthdate;
$_SESSION['user']['phone'] = $phone;

header("Location: profile.php");
exit();

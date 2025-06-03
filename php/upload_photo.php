<?php
session_start();
include 'database.php';

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
    $fileName = $_FILES['profile_photo']['name'];
    $fileSize = $_FILES['profile_photo']['size'];
    $fileType = $_FILES['profile_photo']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
        $uploadFileDir = realpath(__DIR__ . '/../admin/uploads') . '/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Simpan path foto ke database
            $userId = $_SESSION['user']['id']; // pastikan kolom 'id' tersedia di session
            $stmt = $pdo->prepare("UPDATE users SET profile_photo = ? WHERE id = ?");
            $stmt->execute([$dest_path, $userId]);

            // Update session
            $_SESSION['user']['profile_photo'] = $dest_path;

            header("Location: profile.php?success=1");
            exit();
        } else {
            echo "Gagal memindahkan file.";
        }
    } else {
        echo "Ekstensi file tidak diizinkan. Hanya jpg, jpeg, png.";
    }
} else {
    echo "Tidak ada file diupload atau terjadi error.";
}
?>

<?php
include 'database.php';
session_start();


if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$error = '';

// Proses Login
if (isset($_POST['login'])) {
    $login_input = $_POST['login_input'] ?? '';
    $password = $_POST['password'] ?? '';

    // Admin login
    if ($login_input === 'admin@gmail.com' && $password === 'admin-pagestation') {
        $_SESSION['user'] = [
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin'
        ];
        header("Location: admin/admin.php");
        exit();
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $login_input, $login_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: ../index.php");
                exit();
            } else {
                $error = "Password salah, coba ingat-ingat lagi.";
            }
        } else {
            $error = "Pengguna tidak ditemukan.";
        }
    }
}

// Proses Registrasi
if (isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Cek apakah username sudah dipakai
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username sudah digunakan. Silakan pilih yang lain.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Gagal mendaftar. Coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <style>
       
    </style>
</head>
<body>
<div class="container" id="container">
    <!-- Form Registrasi -->
    <div class="form-container sign-up">
        <form method="POST">
            <h1>Buat akun</h1>
            
            <input type="text" name="name" placeholder="Name" required value="<?= $_POST['name'] ?? '' ?>">
            <input type="email" name="email" placeholder="Email" required value="<?= $_POST['email'] ?? '' ?>">
            <div class="password-container">
                <input type="password" id="register-password" name="password" placeholder="Password" required>
                <i class="fa-solid fa-eye eye-icon" onclick="togglePassword('register-password', this)"></i>
            </div>
            <button type="submit" name="register">Sign Up</button>
            <?php if (!empty($error) && isset($_POST['register'])): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>

    <!-- Form Login -->
    <div class="form-container sign-in">
        <form method="POST">
            <h1>Masuk Akun</h1>
           
            <input type="text" name="login_input" placeholder="Email atau Username" required>
            <div class="password-container">
                <input type="password" id="login-password" name="password" placeholder="Password" required>
                <i class="fa-solid fa-eye eye-icon" onclick="togglePassword('login-password', this)"></i>
            </div>
            <a href="#">Lupa password?</a>
            <button type="submit" name="login">Masuk</button>
            <?php if (!empty($error) && isset($_POST['login'])): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>

    <!-- Toggle Panel -->
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Selamat datang</h1>
                <p>Silahkan isi form pendaftaran</p>
                <button class="hidden" id="login">Masuk</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hai bro</h1>
                <p>Belum punya akun?</p>
                <button class="hidden" id="register">Daftar</button>
            </div>
        </div>
    </div>
</div>

<script src="../js/login.js"></script>
<script>
    function togglePassword(id, icon) {
        const input = document.getElementById(id);
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>
</body>
</html>

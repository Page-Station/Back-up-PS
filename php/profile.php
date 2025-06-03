<?php
include 'database.php'; // Hubungkan ke database

session_start();

// Jika belum login, redirect ke login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil data user dari session atau database (disini contoh dari session)
$user = $_SESSION['user'];

// Jika ingin ambil data terbaru dari database, bisa query ulang berdasarkan user id/email

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="../css/profile.css" />
</head>

<body>
    <div class="container">
        <h1>Profil Pengguna</h1>

        <div class="profile-header">
            <div class="profile-photo" id="profilePhoto" style="background-image: url('<?php echo isset($user['profile_photo']) ? $user['profile_photo'] : '../image/default-profile.png'; ?>');"></div>
            <div>
                <form id="uploadPhotoForm" action="upload_photo.php" method="POST" enctype="multipart/form-data" style="display:none;">
                    <input type="file" name="profile_photo" id="profilePhotoInput" accept=".jpg,.jpeg,.png" />
                    <button type="submit" class="btn">Upload Foto</button>
                </form>
                <span class="btn-upload" id="btnChangePhoto">Ubah Foto Profil</span>
            </div>
        </div>

        <form id="profileForm" action="update_profile.php" method="POST">
            <div class="form-group">
                <label for="username"><strong>Username:</strong></label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly />
            </div>

            <div class="form-group">
                <label for="fullname"><strong>Nama Lengkap:</strong></label>
                <input type="text" id="fullname" name="fullname" value="<?php echo isset($user['fullname']) ? htmlspecialchars($user['fullname']) : ''; ?>" required />
            </div>

            <div class="form-group">
                <label for="email"><strong>Email:</strong></label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
            </div>

            <div class="form-group">
                <label for="gender"><strong>Jenis Kelamin:</strong></label>
                <select id="gender" name="gender" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php if (isset($user['gender']) && $user['gender'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if (isset($user['gender']) && $user['gender'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                    <option value="Lainnya" <?php if (isset($user['gender']) && $user['gender'] == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthdate"><strong>Tanggal Lahir:</strong></label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($user['birthdate']) ? $user['birthdate'] : ''; ?>" required />
            </div>

            <div class="form-group">
                <label for="phone"><strong>No. Telepon:</strong></label>
                <input type="tel" id="phone" name="phone" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" />
            </div>

            <button type="submit" class="btn">Simpan Perubahan</button>
        </form>

        <p style="margin-top: 20px;">
            <a href="#" id="btnChangePassword">Atur Kata Sandi</a>
        </p>

        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>

    <!-- Modal Ganti Kata Sandi -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="closePasswordModal">&times;</span>
            <h2>Kata Sandi</h2>
            <p>Buat kata sandi yang kuat untuk akunmu</p>
            <form id="passwordForm" action="update_password.php" method="POST">
                <div class="form-group">
                    <input type="password" id="newPassword" name="new_password" placeholder="Kata Sandi" required />
                </div>
                <div class="form-group">
                    <input type="password" id="confirmPassword" name="confirm_password" placeholder="Konfirmasi Kata Sandi" required />
                </div>
                <ul class="password-guidelines">
                    <li>✔ Minimum 8 karakter.</li>
                    <li>✔ Sertakan huruf kapital & non-kapital.</li>
                    <li>✔ Sertakan angka & simbol.</li>
                </ul>
                <button type="submit" class="btn" id="savePasswordBtn" disabled>Simpan</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle upload photo form
        document.getElementById('btnChangePhoto').addEventListener('click', function () {
            const form = document.getElementById('uploadPhotoForm');
            form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
        });

        // Modal password
        const passwordModal = document.getElementById('passwordModal');
        const btnChangePassword = document.getElementById('btnChangePassword');
        const closePasswordModal = document.getElementById('closePasswordModal');
        const savePasswordBtn = document.getElementById('savePasswordBtn');
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        btnChangePassword.addEventListener('click', function (e) {
            e.preventDefault();
            passwordModal.style.display = 'block';
        });

        closePasswordModal.addEventListener('click', function () {
            passwordModal.style.display = 'none';
            clearPasswordFields();
        });

        window.addEventListener('click', function (event) {
            if (event.target == passwordModal) {
                passwordModal.style.display = 'none';
                clearPasswordFields();
            }
        });

        function clearPasswordFields() {
            newPassword.value = '';
            confirmPassword.value = '';
            savePasswordBtn.disabled = true;
        }

        // Validasi password
        function validatePassword() {
            const pwd = newPassword.value;
            const confirmPwd = confirmPassword.value;

            // Minimal 8 karakter, ada huruf besar, huruf kecil, angka, simbol
            const strongPwd = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (strongPwd.test(pwd) && pwd === confirmPwd) {
                savePasswordBtn.disabled = false;
            } else {
                savePasswordBtn.disabled = true;
            }
        }

        newPassword.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);
    </script>
</body>

</html>

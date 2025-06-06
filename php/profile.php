<?php
include 'database.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$photoPath = isset($user['profile_photo']) ? '../php/' . $user['profile_photo'] : '../image/default-profile.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengaturan Profil</title>
    <link rel="stylesheet" href="../css/profile.css" />
    <style>
        .profile-section {
            margin-bottom: 25px;
        }
        .profile-section label {
            font-weight: 500;
            color: #666;
            font-size: 14px;
        }
        .profile-section .value {
            font-weight: 700;
            font-size: 16px;
            margin-top: 4px;
        }
        .profile-section .value a {
            color: #007bff;
            text-decoration: underline;
            font-weight: normal;
        }
        .profile-section .value a:hover {
            text-decoration: none;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 3px solid #007bff;
        }
        .btn-upload {
            background-color: transparent;
            color: #007bff;
            border: 1px solid #007bff;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-upload:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Pengaturan Profil</h1>

    <div class="profile-header">
        <div class="profile-photo" id="profilePhoto" style="background-image: url('<?php echo $photoPath; ?>');"></div>
        <div>
            <form id="uploadPhotoForm" action="upload_photo.php" method="POST" enctype="multipart/form-data" style="display:none;">
                <input type="file" name="profile_photo" id="profilePhotoInput" accept=".jpg,.jpeg,.png" />
                <button type="submit" class="btn">Upload Foto</button>
            </form>
            <span class="btn-upload" id="btnChangePhoto">Ubah Foto Profil</span>
        </div>
    </div>

    <div class="profile-section">
        <label>Nama Lengkap</label>
        <div class="value">
            <?php echo htmlspecialchars($user['fullname']); ?>
            <a href="#" onclick="openEditModal('fullname', '<?php echo htmlspecialchars($user['fullname']); ?>')">✏️</a>
        </div>
    </div>

    <div class="profile-section">
        <label>Email</label>
        <div class="value"><?php echo htmlspecialchars($user['email']); ?></div>
    </div>

    <div class="profile-section">
        <label>Kata Sandi</label>
        <div class="value"><a href="#" onclick="openPasswordModal()">Atur Kata Sandi</a></div>
    </div>

    <div class="profile-section">
        <label>Jenis Kelamin</label>
        <div class="value">
            <?php echo isset($user['gender']) && $user['gender'] ? $user['gender'] : '<a href="#">Pilih Jenis Kelamin</a>'; ?>
        </div>
    </div>

    <div class="profile-section">
        <label>Tanggal Lahir</label>
        <div class="value">
            <?php echo isset($user['birthdate']) && $user['birthdate'] ? $user['birthdate'] : '<a href="#">Pilih Tanggal Lahir</a>'; ?>
        </div>
    </div>

    <div class="profile-section">
        <label>No. Telepon</label>
        <div class="value">
            <?php echo isset($user['phone']) && $user['phone'] ? $user['phone'] : '<a href="#" onclick=\"openEditModal(\'phone\', \'\')\">Masukkan Nomor Telepon</a>'; ?>
            <?php if (!empty($user['phone'])): ?>
                <a href="#" onclick="openEditModal('phone', '<?php echo htmlspecialchars($user['phone']); ?>')">✏️</a>
            <?php endif; ?>
        </div>
    </div>

    <a href="logout.php" class="btn btn-logout">Logout</a>
</div>

<!-- Modal Edit Field -->
<div id="editFieldModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" id="closeEditFieldModal">&times;</span>
        <h2 id="editFieldTitle">Edit</h2>
        <form id="editFieldForm" method="POST" action="update_field.php">
            <input type="hidden" name="field" id="fieldName" />
            <div class="form-group">
                <input type="text" name="value" id="fieldValue" required />
            </div>
            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>
</div>

<!-- Modal Ubah Password -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" id="closePasswordModal">&times;</span>
        <h2>Ubah Kata Sandi</h2>
        <form method="POST" action="update-password.php">
            <div class="form-group">
                <input type="password" name="old_password" placeholder="Kata Sandi Lama" required />
            </div>
            <div class="form-group">
                <input type="password" name="new_password" placeholder="Kata Sandi Baru" required />
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Konfirmasi Kata Sandi Baru" required />
            </div>
            <button type="submit" class="btn">Simpan</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('btnChangePhoto').addEventListener('click', function () {
        const form = document.getElementById('uploadPhotoForm');
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    });

    function openEditModal(field, currentValue) {
        document.getElementById('fieldName').value = field;
        document.getElementById('fieldValue').value = currentValue;
        document.getElementById('editFieldTitle').textContent = field === 'fullname' ? 'Ubah Nama Lengkap' : 'Ubah Nomor Telepon';
        document.getElementById('editFieldModal').style.display = 'block';
    }

    document.getElementById('closeEditFieldModal').addEventListener('click', function () {
        document.getElementById('editFieldModal').style.display = 'none';
    });

    function openPasswordModal() {
        document.getElementById('passwordModal').style.display = 'block';
    }

    document.getElementById('closePasswordModal').addEventListener('click', function () {
        document.getElementById('passwordModal').style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
</script>
</body>
</html>

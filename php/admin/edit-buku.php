<?php
session_start();
include('../database.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Akses ditolak.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID buku tidak ditemukan.";
    exit();
}

$book_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $jenjang = $_POST['jenjang'] ?? null;  // Ambil jenjang jika ada
    $kelas = $_POST['kelas'];      // Ambil kelas jika ada
    if ($kelas === '' || !is_numeric($kelas)) {
        $kelas = null; // atau 0 jika kamu ingin default 0
    } else {
        $kelas = intval($kelas);
    }
    $stock = intval($_POST['stock']);

    $pdf_path = $_POST['existing_pdf'];
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $pdf_path = 'uploads/pdfs/' . basename($_FILES['pdf_file']['name']);
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdf_path);
    }

    $cover_path = $_POST['existing_cover'];
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $cover_path = 'uploads/covers/' . basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_path);
    }

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, description=?, pdf_path=?, category=?, cover_image=?, jenjang=?, kelas=?, stock=? WHERE id=?");
    $stmt->bind_param('ssssssssii', $title, $author, $description, $pdf_path, $category, $cover_path, $jenjang, $kelas, $stock, $book_id);
    $stmt->execute();

    header("Location: data-buku.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../../css/kelola-buku.css">
</head>
<body>
<h2>Edit Buku</h2>
<form action="edit-buku.php?id=<?php echo $book['id']; ?>" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
    <textarea name="description" required><?php echo htmlspecialchars($book['description']); ?></textarea>
    <select name="category" id="category" required>
        <?php
        $categories = ["Pelajaran", "Novel", "Filsafat", "Psikologi", "Dongeng", "Islami", "Self Development"];
        foreach ($categories as $cat) {
            $selected = ($book['category'] == $cat) ? 'selected' : '';
            echo "<option value='$cat' $selected>$cat</option>";
        }
        ?>
    </select>

    <div id="pelajaran-fields" style="display: <?php echo ($book['category'] == 'Pelajaran') ? 'block' : 'none'; ?>;">
        <select name="jenjang" id="jenjang">
            <option value="">Pilih Jenjang</option>
            <?php
            $jenjangs = ['SD', 'SMP', 'SMA/SMK'];
            foreach ($jenjangs as $j) {
                $selected = ($book['jenjang'] == $j) ? 'selected' : '';
                echo "<option value='$j' $selected>$j</option>";
            }
            ?>
        </select>

        <select name="kelas" id="kelas">
            <option value="">Pilih Kelas</option>
            <?php
            // Tentukan kelas sesuai jenjang yang sudah dipilih
            $kelas_options = [];
            if ($book['jenjang'] == 'SD') {
                $kelas_options = range(1, 6);
            } elseif ($book['jenjang'] == 'SMP') {
                $kelas_options = range(7, 9);
            } elseif ($book['jenjang'] == 'SMA/SMK') {
                $kelas_options = range(10, 12);
            }
            foreach ($kelas_options as $k) {
                $selected = ($book['kelas'] == $k) ? 'selected' : '';
                echo "<option value='$k' $selected>Kelas $k</option>";
            }
            ?>
        </select>
    </div>

    <input type="number" name="stock" placeholder="Stok Buku" min="0" value="<?php echo intval($book['stock']); ?>" required>

    <p>PDF Saat Ini: <?php echo $book['pdf_path'] ? "<a href='".$book['pdf_path']."' target='_blank'>Lihat</a>" : "Tidak ada"; ?></p>
    <input type="file" name="pdf_file" accept=".pdf">

    <p>Cover Saat Ini:
        <?php if ($book['cover_image']): ?>
            <img src="<?php echo $book['cover_image']; ?>" style="width:80px;">
        <?php else: ?>
            Tidak ada
        <?php endif; ?>
    </p>
    <input type="file" name="cover_image" accept="image/*">

    <input type="hidden" name="existing_pdf" value="<?php echo htmlspecialchars($book['pdf_path']); ?>">
    <input type="hidden" name="existing_cover" value="<?php echo htmlspecialchars($book['cover_image']); ?>">

    <button type="submit">Update</button>
</form>

<script>
document.getElementById('category').addEventListener('change', function() {
    const pelajaranFields = document.getElementById('pelajaran-fields');
    pelajaranFields.style.display = this.value === 'Pelajaran' ? 'block' : 'none';
});

document.getElementById('jenjang').addEventListener('change', function() {
    const jenjang = this.value;
    const kelas = document.getElementById('kelas');
    kelas.innerHTML = '<option value="">Pilih Kelas</option>';
    let jumlah = jenjang === 'SD' ? 6 : (jenjang === 'SMP' ? 3 : 3);
    let start = jenjang === 'SD' ? 1 : (jenjang === 'SMP' ? 7 : 10);
    for (let i = 0; i < jumlah; i++) {
        const option = document.createElement('option');
        option.value = start + i;
        option.textContent = 'Kelas ' + (start + i);
        kelas.appendChild(option);
    }
});
</script>
</body>
</html>

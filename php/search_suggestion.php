<?php
include('database.php');

header('Content-Type: application/json');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($q) < 3) {
    echo json_encode(['error' => 'Minimal 3 karakter']);
    exit;
}

$keyword = "%$q%";

$stmt = $conn->prepare("SELECT id, title, author, description FROM books WHERE title LIKE ? OR author LIKE ? LIMIT 5");
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

// Contoh juga cari penulis yang cocok (distinct)
$stmt2 = $conn->prepare("SELECT DISTINCT author FROM books WHERE author LIKE ? LIMIT 3");
$stmt2->bind_param("s", $keyword);
$stmt2->execute();
$result2 = $stmt2->get_result();

$authors = [];
while ($row = $result2->fetch_assoc()) {
    $authors[] = $row['author'];
}

echo json_encode([
    'books' => $books,
    'authors' => $authors
]);
?>

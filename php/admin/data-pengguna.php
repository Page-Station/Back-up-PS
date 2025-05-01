<?php
session_start();
include('../database.php');


// Ambil daftar buku
$query_books = "SELECT * FROM books";
$result_books = $conn->query($query_books);

// Ambil daftar pengguna
$query_users = "SELECT * FROM users";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data pengguna</title>
    <link rel="stylesheet" type="text/css" href="../css/../kelola-buku&pengguna.css">
</head>
<body>
<section class="users-section">
            <h2>Daftar Pengguna</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Buku yang Dibaca</th>
                </tr>
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['username']; ?></td>
                        <td>
                            <?php 
                            if ($user['book_id']) {
                                $book_query = "SELECT title FROM books WHERE id = ?";
                                $stmt_book = $conn->prepare($book_query);
                                $stmt_book->bind_param('i', $user['book_id']);
                                $stmt_book->execute();
                                $book_result = $stmt_book->get_result();
                                $book = $book_result->fetch_assoc();
                                echo $book['title'];
                            } else {
                                echo "Tidak ada buku yang dibaca";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>
</body>
</html>
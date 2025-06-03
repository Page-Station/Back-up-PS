<?php
$host = "localhost";
$user = "page-station";
$password = "alamarekh1006";
$dbname = "page-station-db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
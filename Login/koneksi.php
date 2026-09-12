<?php
$host     = "localhost";
$user     = "root";      // Username default XAMPP
$password = "";          // Kosongkan jika default XAMPP
$database = "db_internspace";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
<?php
$host     = "localhost";
$user     = "root";      // Username default XAMPP / Laragon
$password = "";          // Kosongkan jika default
$database = "db_internspace";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
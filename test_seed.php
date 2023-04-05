<?php
require 'config.php';

$nama = 'Admin';
$email = 'admin@gmail.com';
$password = 'password';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$ponsel = '1234567890';
$tanggal = date('Y-m-d');

$sql = "INSERT INTO users (nama, email, password, ponsel, tanggal) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $hashed_password, $ponsel, $tanggal);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "Data seed berhasil dibuat.\n";
} else {
    echo "Gagal membuat data seed: " . mysqli_error($db) . "\n";
}

mysqli_stmt_close($stmt);
mysqli_close($db);

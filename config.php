<?php
session_start();

// Konfigurasi Database
$host = "localhost";
$username = "root";  // default XAMPP
$password = "";      // default XAMPP (kosong)
$database = "ai_bijak_db";

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Fungsi untuk hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fungsi untuk verifikasi password
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.html");
        exit();
    }
}

// Redirect jika sudah login
function requireLogout() {
    if (isLoggedIn()) {
        header("Location: dashboard.html");
        exit();
    }
}
?>
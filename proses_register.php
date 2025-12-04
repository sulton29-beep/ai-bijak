<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi input
    if (empty($nama) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        header("Location: register.html?error=" . urlencode("Semua field wajib diisi!"));
        exit();
    }
    
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?error=" . urlencode("Format email tidak valid!"));
        exit();
    }
    
    // Cek apakah email sudah terdaftar
    $checkEmail = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmail);
    
    if (mysqli_num_rows($result) > 0) {
        header("Location: register.html?error=" . urlencode("Email sudah terdaftar!"));
        exit();
    }
    
    // Validasi password
    if (strlen($password) < 6) {
        header("Location: register.html?error=" . urlencode("Password minimal 6 karakter!"));
        exit();
    }
    
    if ($password !== $confirm_password) {
        header("Location: register.html?error=" . urlencode("Password tidak cocok!"));
        exit();
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert ke database
    $sql = "INSERT INTO users (username, email, no_hp, password, role) 
            VALUES ('$nama', '$email', '$no_hp', '$hashedPassword', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        // Catat aktivitas
        $userId = mysqli_insert_id($conn);
        $activity = "Mendaftar akun baru";
        $logSql = "INSERT INTO user_logs (user_id, activity) VALUES ($userId, '$activity')";
        mysqli_query($conn, $logSql);
        
        header("Location: register.html?success=" . urlencode("Pendaftaran berhasil! Silakan login."));
        exit();
    } else {
        header("Location: register.html?error=" . urlencode("Gagal mendaftar. Coba lagi!"));
        exit();
    }
} else {
    header("Location: register.html");
    exit();
}
?>
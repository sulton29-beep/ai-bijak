<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Cek apakah input email atau no hp
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Jika email
        $sql = "SELECT * FROM users WHERE email = '$email'";
    } else {
        // Jika nomor handphone
        $sql = "SELECT * FROM users WHERE no_hp = '$email'";
    }
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['no_hp'] = $user['no_hp'];
            $_SESSION['login_time'] = time();
            
            // Catat login ke database
            $userId = $user['id'];
            $activity = "Login ke sistem";
            $logSql = "INSERT INTO user_logs (user_id, activity) VALUES ($userId, '$activity')";
            mysqli_query($conn, $logSql);
            
            // Redirect ke dashboard.php
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.html?error=" . urlencode("Password salah!"));
            exit();
        }
    } else {
        header("Location: login.html?error=" . urlencode("Email/nomor handphone tidak ditemukan!"));
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
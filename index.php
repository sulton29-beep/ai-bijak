<?php
// index.php
session_start();

if (isset($_SESSION['user_id'])) {
    // Jika sudah login, redirect ke dashboard
    header("Location: dashboard.php");
} else {
    // Jika belum login, redirect ke login
    header("Location: login.html");
}
exit();
?>
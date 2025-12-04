<?php
// reset-password.php
$host = "localhost";
$username = "root";
$password = "";
$database = "ai_bijak_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal");
}

// Hash untuk password 123456
$hashed_password = password_hash('123456', PASSWORD_DEFAULT);

echo "Password hash untuk '123456':<br>";
echo "<strong>$hashed_password</strong><br><br>";

// Update password semua user
$sql = "UPDATE users SET password = '$hashed_password' WHERE 1";
if (mysqli_query($conn, $sql)) {
    echo "✅ Password berhasil direset ke '123456' untuk semua user!<br>";
    echo "Sekarang bisa login dengan:<br>";
    echo "- Email: admin@gmail.com<br>";
    echo "- Password: 123456<br>";
    echo "<br><a href='login.html'>Login Sekarang</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
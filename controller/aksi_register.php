<?php
require_once __DIR__ . '/../models/dbConfig.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    $user = query("SELECT * FROM users WHERE username = '$username'");
    if (!empty($user)) {
        echo "<script>alert('Username Sudah Ada, Tolong Lakukan Register Kembali'); window.location.href='/register.php';</script>";
    } else {
        // Validasi password
        if ($password !== $verify_password) {
            echo "<script>alert('Password does not match'); window.location.href='/register.php';</script>";
            exit;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan data user baru ke database
        $sql = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            // Jika registrasi berhasil, tambahkan data ke tabel leaderboard
            $userID = mysqli_insert_id($koneksi); // Mendapatkan ID user yang baru saja dibuat
            $sql_leaderboard = "INSERT INTO leaderboard (userID, totalBeli) VALUES (?, ?)";
            $stmt_leaderboard = mysqli_prepare($koneksi, $sql_leaderboard);
            $initial_total_beli = 0;
            mysqli_stmt_bind_param($stmt_leaderboard, "ii", $userID, $initial_total_beli);

            if (mysqli_stmt_execute($stmt_leaderboard)) {
                echo "<script>alert('Registration successful'); window.location.href='/login.php';</script>";
            } else {
                echo "<script>alert('Registration failed: " . mysqli_error($koneksi) . "'); window.location.href='/register.php';</script>";
            }
            mysqli_stmt_close($stmt_leaderboard);
        } else {
            echo "<script>alert('Registration failed: " . mysqli_error($koneksi) . "'); window.location.href='/register.php';</script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
    }
} else {
    echo "Invalid request method";
}

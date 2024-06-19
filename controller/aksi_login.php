<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Query untuk mendapatkan data user dari database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row;

            if ($row['id'] == 1) {
                $_SESSION['admin'] = true;
                header("Location: /views/admin/dashAdmin.php");
            } else {
                header("Location: /views/user/dashUser.php");
            }
        } else {
            echo "<script>alert('Invalid credentials'); window.location.href='/login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials'); window.location.href='/login.php';</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}

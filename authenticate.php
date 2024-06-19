<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// Example credentials check (replace with your database check)
if ($username == 'admin' && $password == 'admin123') {
    $_SESSION['user'] = 'admin';
    header("Location: /views/admin/dashAdmin.php");
} elseif ($username == 'user' && $password == 'user123') {
    $_SESSION['user'] = 'user';
    header("Location: /views/user/dashUser.php");
} else {
    echo "Invalid credentials";
}

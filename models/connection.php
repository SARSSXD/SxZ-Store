<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "webdmstore";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone to Asia/Jakarta for MySQL
$koneksi->query("SET time_zone = '+07:00'");

// Optionally set timezone for PHP if not already set globally
date_default_timezone_set('Asia/Jakarta');
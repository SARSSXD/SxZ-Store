<?php
require_once __DIR__ . '/connection.php'; // Include connection.php

function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Set timezone to Asia/Jakarta for MySQL
$koneksi->query("SET time_zone = '+07:00'");

// Optionally set timezone for PHP if not already set globally
date_default_timezone_set('Asia/Jakarta');

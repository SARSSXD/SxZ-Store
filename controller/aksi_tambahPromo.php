<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa apakah semua data yang diperlukan tersedia dalam $_POST
    if (isset($_POST['nama'], $_POST['minimBeli'], $_POST['kadaluwarsa'], $_POST['persen'])) {
        // Escape input untuk menghindari SQL injection
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $minimBeli = intval($_POST['minimBeli']);
        $kadaluwarsa = $_POST['kadaluwarsa']; 
        $persen = intval($_POST['persen']);

        // Query SQL untuk menambahkan promo baru ke database
        $sql = "INSERT INTO promo (nama, minimBeli, kadaluwarsa, persen) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, 'sdsd', $nama, $minimBeli, $kadaluwarsa, $persen);

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Promo berhasil ditambahkan'); window.location.href='../views/admin/promo.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan promo'); window.location.href='../views/admin/tambahPromo.php';</script>";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        // Jika tidak semua data diberikan, berikan pesan kesalahan
        echo "<script>alert('Semua field harus diisi'); window.location.href='../views/admin/tambahPromo.php';</script>";
    }
} else {
    // Jika metode request tidak valid, berikan pesan kesalahan
    echo "<script>alert('Metode request tidak valid'); window.location.href='../views/admin/tambahPromo.php';</script>";
}

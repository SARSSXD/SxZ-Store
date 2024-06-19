<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama'], $_POST['kategori'], $_POST['deskripsi'], $_POST['kontak'])) {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
        $kontak = mysqli_real_escape_string($koneksi, $_POST['kontak']);

        $sql = "INSERT INTO report (nama, jenis, deskripsi, kontak) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $kategori, $deskripsi, $kontak);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Laporan berhasil diajukan'); window.location.href='/views/user/report.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengajukan laporan'); window.location.href='/views/user/report.php';</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Semua field harus diisi'); window.location.href='/views/user/report.php';</script>";
    }
} else {
    echo "<script>alert('Metode request tidak valid'); window.location.href='/views/user/report.php';</script>";
}

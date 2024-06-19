<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['rating'], $_POST['komentar'], $_POST['noInvoice'])) {
        $rating = mysqli_real_escape_string($koneksi, $_POST['rating']);
        $komentar = mysqli_real_escape_string($koneksi, $_POST['komentar']);
        $noInvoice = mysqli_real_escape_string($koneksi, $_POST['noInvoice']);
        $review = query("SELECT * FROM review WHERE noInvoice = '$noInvoice'");
        if (empty($review)) {

            // var_dump($noInvoice);
            // var_dump($rating);
            // var_dump($komentar);
            $sql = "INSERT INTO review (noInvoice, nilai, komentar) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sis", $noInvoice, $rating, $komentar);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Ulasan berhasil disubmit'); window.location.href='/views/user/cekTransaksi.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menyimpan ulasan'); window.location.href='/views/user/cekTransaksi';</script>";
            }

            mysqli_stmt_close($stmt);
        } else {
            $sql = "UPDATE review SET nilai = ?, komentar = ? WHERE noInvoice = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $rating, $komentar, $noInvoice);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Ulasan berhasil diedit'); window.location.href='/views/user/cekTransaksi.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat mengedit ulasan'); window.location.href='/views/user/cekTransaksi';</script>";
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        echo "<script>alert('Semua field harus diisi'); window.location.href='/views/user/cekTransaksi.php';</script>";
    }
} else {
    echo "<script>alert('Metode request tidak valid'); window.location.href='/views/user/cekTransaksi.php';</script>";
}

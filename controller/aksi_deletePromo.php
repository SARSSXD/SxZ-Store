<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Hapus promo berdasarkan ID
    $sql_delete = "DELETE FROM promo WHERE id = ?";
    $stmt_delete = mysqli_prepare($koneksi, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, 'i', $id);
    if (mysqli_stmt_execute($stmt_delete)) {
        mysqli_stmt_close($stmt_delete);

        // Mendapatkan nilai maksimum ID yang tersisa dalam tabel
        $max_id_result = mysqli_query($koneksi, "SELECT MAX(id) AS max_id FROM promo");
        $max_id_row = mysqli_fetch_assoc($max_id_result);
        $max_id = intval($max_id_row['max_id']);

        // Set auto-increment menjadi nilai maksimum ID + 1
        $reset_auto_increment_sql = "ALTER TABLE promo AUTO_INCREMENT = " . ($max_id + 1);
        if (mysqli_query($koneksi, $reset_auto_increment_sql)) {
            echo "<script>alert('Promo berhasil dihapus dan auto increment direset'); window.location.href='/views/admin/promo.php';</script>";
        } else {
            echo "<script>alert('Promo berhasil dihapus, tetapi terjadi kesalahan saat mereset auto increment'); window.location.href='/views/admin/promo.php';</script>";
        }
    } else {
        mysqli_stmt_close($stmt_delete);
        echo "<script>alert('Terjadi kesalahan saat menghapus promo'); window.location.href='/views/admin/promo.php';</script>";
    }

    mysqli_close($koneksi);
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='/views/admin/promo.php';</script>";
}

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Lakukan pengecekan apakah nominal dengan ID yang diberikan ada dalam database
    $nominal = query("SELECT * FROM nominal WHERE id = $id");

    if (!$nominal) {
        echo "<script>alert('Nominal tidak ditemukan!'); window.location.href='../views/admin/item.php';</script>";
        exit;
    }

    // Hapus nominal dari database
    $sql = "DELETE FROM nominal WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        // Hapus juga gambar terkait dari direktori jika ada
        $gambar = $nominal[0]['gambar'];
        if (!empty($gambar)) {
            $gambar_path = __DIR__ . '/../assets/img/nominal/' . $gambar;
            if (file_exists($gambar_path)) {
                unlink($gambar_path);
            }
        }

        // Mendapatkan nilai maksimum ID yang tersisa dalam tabel
        $max_id_result = mysqli_query($koneksi, "SELECT MAX(id) AS max_id FROM nominal");
        $max_id_row = mysqli_fetch_assoc($max_id_result);
        $max_id = intval($max_id_row['max_id']);

        // Set auto-increment menjadi nilai maksimum ID + 1
        $reset_auto_increment_sql = "ALTER TABLE nominal AUTO_INCREMENT = " . ($max_id + 1);
        mysqli_query($koneksi, $reset_auto_increment_sql);

        echo "<script>alert('Nominal berhasil dihapus'); window.location.href='../views/admin/item.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus nominal'); window.location.href='../views/admin/item.php';</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
} else {
    echo "<script>alert('Metode request tidak valid atau ID nominal tidak diberikan'); window.location.href='../views/admin/item.php';</script>";
}

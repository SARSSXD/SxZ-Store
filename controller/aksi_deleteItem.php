<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Lakukan pengecekan apakah item dengan ID yang diberikan ada dalam database
    $item = query("SELECT * FROM item WHERE id = $id");

    if (!$item) {
        echo json_encode(array("status" => "error", "message" => "Item tidak ditemukan!"));
        exit;
    }

    // Hapus item dari database
    $sql = "DELETE FROM item WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        // Hapus juga gambar terkait dari direktori
        $gambar = $item[0]['gambar'];
        if (!empty($gambar)) {
            $gambar_path = __DIR__ . '/../assets/img/card/' . $gambar;
            if (file_exists($gambar_path)) {
                unlink($gambar_path);
            }
        }

        // Mendapatkan nilai maksimum ID yang tersisa dalam tabel
        $max_id_result = mysqli_query($koneksi, "SELECT MAX(id) AS max_id FROM item");
        $max_id_row = mysqli_fetch_assoc($max_id_result);
        $max_id = intval($max_id_row['max_id']);

        // Set auto-increment menjadi nilai maksimum ID + 1
        $reset_auto_increment_sql = "ALTER TABLE item AUTO_INCREMENT = " . ($max_id + 1);
        mysqli_query($koneksi, $reset_auto_increment_sql);

        echo "<script>alert('Item berhasil dihapus'); window.location.href='../views/admin/item.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus item'); window.location.href='../views/admin/item.php';</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
} else {
    echo json_encode(array("status" => "error", "message" => "Metode request tidak valid atau ID item tidak diberikan"));
}

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/connection.php'; // Gunakan path absolut relatif terhadap file ini

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['product_name']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $uploadOk = 1;
    $gambar = '';

    // Mengambil gambar lama dari database
    $sql = "SELECT gambar FROM item WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);
    $gambar_lama = $item['gambar'];
    mysqli_stmt_close($stmt);

    // Mengelola upload file
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = __DIR__ . '/../assets/img/card/';
        $image_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_file_name = $id . '.' . $image_extension; // Nama file baru hanya berisi ID
        $target_file = $target_dir . $new_file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Memeriksa apakah file yang diupload adalah gambar
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File yang diupload bukan gambar.";
            $uploadOk = 0;
        }

        // Memeriksa ukuran file
        if ($_FILES["image"]["size"] > 5000000) { // 5MB
            echo "Maaf, file Anda terlalu besar.";
            $uploadOk = 0;
        }

        // Memeriksa format file
        $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_file_types)) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Memeriksa apakah $uploadOk bernilai 0 karena kesalahan
        if ($uploadOk === 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Menghapus gambar lama
                if (!empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
                    unlink($target_dir . $gambar_lama);
                }
                $gambar = $new_file_name;
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
                $uploadOk = 0;
            }
        }
    }

    // Memperbarui data di database
    if ($uploadOk === 1) {
        if (!empty($gambar)) {
            $sql = "UPDATE item SET nama = ?, kategori = ?, gambar = ? WHERE id = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nama, $kategori, $gambar, $id);
        } else {
            $sql = "UPDATE item SET nama = ?, kategori = ? WHERE id = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $nama, $kategori, $id);
        }

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Item berhasil diperbarui'); window.location.href='../views/admin/item.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat memperbarui item'); window.location.href='../views/admin/editItem.php?id=$id';</script>";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($koneksi);
}

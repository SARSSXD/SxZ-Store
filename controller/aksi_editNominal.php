<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $itemID = intval($_POST['item_id']);
    $nominal = mysqli_real_escape_string($koneksi, $_POST['nominal']);
    $harga = intval($_POST['harga']);
    $uploadOk = 1;
    $gambar = '';

    // Mengambil gambar lama dari database
    $sql = "SELECT gambar FROM nominal WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        echo "Error: " . mysqli_error($koneksi);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $nominal_item = mysqli_fetch_assoc($result);
    $gambar_lama = $nominal_item['gambar'];
    mysqli_stmt_close($stmt);

    // Mengelola upload file
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = __DIR__ . '/../assets/img/nominal/';
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_file_name = $id . '.' . $imageFileType;
        $target_file = $target_dir . $new_file_name;

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
            $sql = "UPDATE nominal SET nominal = ?, harga = ?, gambar = ? WHERE id = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sisi", $nominal, $harga, $gambar, $id);
        } else {
            $sql = "UPDATE nominal SET nominal = ?, harga = ? WHERE id = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $nominal, $harga, $id);
        }

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Nominal berhasil diperbarui'); window.location.href='../views/admin/tambahNominal.php?id=$itemID';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat memperbarui nominal'); window.location.href='../views/admin/editNominal.php?id=$id';</script>";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($koneksi);
}

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['product_name']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

    // Mengelola upload file
    $target_dir = __DIR__ . "/../assets/img/card/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $image_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Memeriksa apakah file yang diupload adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File yang diupload bukan gambar.";
        $uploadOk = 0;
    }

    // Memeriksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Memeriksa ukuran file
    if ($_FILES["image"]["size"] > 5000000) { // 5MB
        echo "Maaf, file Anda terlalu besar.";
        $uploadOk = 0;
    }

    // Memeriksa format file
    $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($image_extension, $allowed_file_types)) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Memeriksa apakah $uploadOk bernilai 0 karena kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak diupload.";
    } else {
        // Jika semua kondisi di atas terpenuhi, coba upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $gambar = basename($_FILES["image"]["name"]);

            // Menyimpan data ke database
            $sql = "INSERT INTO item (nama, kategori, gambar) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $nama, $kategori, $gambar);
            if (mysqli_stmt_execute($stmt)) {
                $id = mysqli_insert_id($koneksi);
                $gambar_with_id = $id . '.' . $image_extension;
                $new_target_file = $target_dir . $gambar_with_id;
                rename($target_file, $new_target_file);

                // Update nama file gambar di database
                $sql_update = "UPDATE item SET gambar = ? WHERE id = ?";
                $stmt_update = mysqli_prepare($koneksi, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "si", $gambar_with_id, $id);
                if (mysqli_stmt_execute($stmt_update)) {
                    echo "<script>alert('Item berhasil ditambahkan'); window.location.href='../views/admin/item.php';</script>";
                } else {
                    echo "<script>alert('Terjadi kesalahan saat menambahkan item'); window.location.href='../views/admin/tambahItem.php';</script>";
                }
                mysqli_stmt_close($stmt_update);
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan item'); window.location.href='../views/admin/tambahItem.php';</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload file.";
        }
    }

    mysqli_close($koneksi);
}

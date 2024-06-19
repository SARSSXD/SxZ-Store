<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

// Periksa apakah metode request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan itemID telah diset dan merupakan integer
    if (isset($_POST['item_id']) && is_numeric($_POST['item_id'])) {
        $itemID = intval($_POST['item_id']);

        // Escape input untuk menghindari SQL injection
        $nominal = mysqli_real_escape_string($koneksi, $_POST['nominal']);
        $harga = intval($_POST['harga']);

        // Query SQL untuk menambahkan nominal baru ke database
        $sql = "INSERT INTO nominal (itemID, nominal, harga) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $itemID, $nominal, $harga);

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Dapatkan ID nominal yang baru saja ditambahkan
            $nominalID = mysqli_insert_id($koneksi);

            // Mengelola upload file gambar
            $target_dir = __DIR__ . '/../assets/img/nominal/';
            $gambar = '';

            if (!empty($_FILES["image"]["name"])) {
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Memeriksa apakah file yang diupload adalah gambar
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check === false) {
                    echo "File yang diupload bukan gambar.";
                    exit;
                }

                // Memeriksa ukuran file
                if ($_FILES["image"]["size"] > 5000000) { // 5MB
                    echo "Maaf, file Anda terlalu besar.";
                    exit;
                }

                // Memeriksa format file
                $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
                if (!in_array($imageFileType, $allowed_file_types)) {
                    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
                    exit;
                }

                // Jika semua kondisi terpenuhi, coba upload file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $gambar = $nominalID . '.' . $imageFileType; // Rename gambar dengan ID nominal
                    $new_target_file = $target_dir . $gambar;
                    rename($target_file, $new_target_file);

                    // Update nama file gambar di database
                    $sql_update = "UPDATE nominal SET gambar = ? WHERE id = ?";
                    $stmt_update = mysqli_prepare($koneksi, $sql_update);
                    mysqli_stmt_bind_param($stmt_update, "si", $gambar, $nominalID);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);

                    echo "<script>alert('Nominal berhasil ditambahkan'); window.location.href='../views/admin/tambahNominal.php?id=$itemID';</script>";
                } else {
                    echo "Maaf, terjadi kesalahan saat mengupload file.";
                    exit;
                }
            } else {
                echo "<script>alert('Nominal berhasil ditambahkan tanpa gambar'); window.location.href='../views/admin/tambahNominal.php?id=$itemID';</script>";
            }
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan nominal'); window.location.href='../views/admin/tambahItem.php';</script>";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Item ID tidak valid.";
    }
} else {
    echo "Metode request tidak valid.";
}

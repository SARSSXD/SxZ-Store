<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaksiID = mysqli_real_escape_string($koneksi, $_POST['transaksiID']);
    if (isset($_SESSION['user']['id'])) {
        $userID = intval($_SESSION['user']['id']);
    } else {
        $userID = null;
    }
    $amount = intval($_POST['amount']);
    $itemID = intval($_POST['itemID']);
    $dataBeliID = intval($_POST['dataBeliID']);
    $buktiID = intval($_POST['buktiID']);

    // Mengelola upload file
    $target_dir = __DIR__ . "/../assets/img/bukti/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $image_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Memeriksa apakah file yang diupload adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File yang diupload bukan gambar.');</script>";
        $uploadOk = 0;
    }

    // Memeriksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo "<script>alert('Maaf, file sudah ada.');</script>";
        $uploadOk = 0;
    }

    // Memeriksa ukuran file
    if ($_FILES["image"]["size"] > 5000000) { // 5MB
        echo "<script>alert('Maaf, file Anda terlalu besar.');</script>";
        $uploadOk = 0;
    }

    // Memeriksa format file
    $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($image_extension, $allowed_file_types)) {
        echo "<script>alert('Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.');</script>";
        $uploadOk = 0;
    }

    // Memeriksa apakah $uploadOk bernilai 0 karena kesalahan
    if ($uploadOk == 0) {
        echo "<script>alert('Maaf, file Anda tidak diupload.');</script>";
    } else {
        // Jika semua kondisi di atas terpenuhi, coba upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update data bukti pembayaran di database
            $buktiBayar_with_id = $buktiID . '.' . $image_extension;
            $new_target_file = $target_dir . $buktiBayar_with_id;
            rename($target_file, $new_target_file);

            // Update nama file gambar dan amount di database
            $sql_update = "UPDATE bukti SET buktiBayar = ?, amount = ? WHERE id = ?";
            $stmt_update = mysqli_prepare($koneksi, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "sii", $buktiBayar_with_id, $amount, $buktiID);
            if (mysqli_stmt_execute($stmt_update)) {
                echo "<script>alert('Bukti pembayaran berhasil diperbarui!'); window.location.href='/views/user/cekTransaksi.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menyimpan data ke database.'); window.location.href='/views/tambahBukti.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
            }
            mysqli_stmt_close($stmt_update);
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengupload file.'); window.location.href='/views/tambahBukti.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
        }
    }

    mysqli_close($koneksi);
}

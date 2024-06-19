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
            // Menyimpan data ke database terlebih dahulu
            $sql = "INSERT INTO bukti (transaksiID, userID, amount) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $transaksiID, $userID, $amount,);
            if (mysqli_stmt_execute($stmt)) {
                $buktiID = mysqli_insert_id($koneksi); // Dapatkan ID terakhir yang diinsert
                $buktiBayar_with_id = $buktiID . '.' . $image_extension;
                $new_target_file = $target_dir . $buktiBayar_with_id;
                rename($target_file, $new_target_file);

                // Update nama file gambar di database
                $sql_update = "UPDATE bukti SET buktiBayar = ? WHERE id = ?";
                $stmt_update = mysqli_prepare($koneksi, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "si", $buktiBayar_with_id, $buktiID);
                if (mysqli_stmt_execute($stmt_update)) {
                    // Jika update berhasil, perbarui kolom buktiID di tabel transaksi
                    $update_transaksi_sql = "UPDATE transaksi SET buktiID = ? WHERE noInvoice = ?";
                    $stmt_update_transaksi = mysqli_prepare($koneksi, $update_transaksi_sql);
                    mysqli_stmt_bind_param($stmt_update_transaksi, "is", $buktiID, $transaksiID);
                    mysqli_stmt_execute($stmt_update_transaksi);
                    mysqli_stmt_close($stmt_update_transaksi);

                    echo "<script>alert('Bukti pembayaran berhasil diunggah!'); window.location.href='/views/user/cekTransaksi.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
                } else {
                    echo "<script>alert('Terjadi kesalahan saat menyimpan data ke database.'); window.location.href='/views/tambahBukti.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
                }
                mysqli_stmt_close($stmt_update);
            } else {
                echo "<script>alert('Terjadi kesalahan saat menyimpan data ke database.'); window.location.href='/views/tambahBukti.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengupload file.'); window.location.href='/views/tambahBukti.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
        }
    }

    mysqli_close($koneksi);
}

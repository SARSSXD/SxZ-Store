<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UID = date('YmdHis');
    $transaksiID = "SAR" . $UID;
    $itemID = intval($_POST['itemID']);
    $user_logged_in = isset($_SESSION['user']);
    if ($user_logged_in) {
        $userID = $_SESSION['user']['id'];
        $promoID = isset($_POST['promo']) ? intval($_POST['promo']) : null;
    } else {
        $userID = null;
        $promoID = null;
    }
    $nominalID = intval($_POST['selected_nominal_id']);
    $dataAkun = mysqli_real_escape_string($koneksi, $_POST['dataAkun']);
    $jumlahBeli = intval($_POST['jumlahBeli']);
    $jenisBayar = mysqli_real_escape_string($koneksi, $_POST['jenisBayar']);
    $kontak = mysqli_real_escape_string($koneksi, $_POST['kontak']);
    var_dump($promoID);
    var_dump($nominalID);
    echo $nominalID;
    var_dump($dataAkun);
    var_dump($jumlahBeli);
    var_dump($itemID);

    // Periksa validitas promoID
    if ($promoID) {
        $query_promo = "SELECT persen FROM promo WHERE id = ?";
        $stmt_promo = mysqli_prepare($koneksi, $query_promo);
        mysqli_stmt_bind_param($stmt_promo, "i", $promoID);
        mysqli_stmt_execute($stmt_promo);
        $result_promo = mysqli_stmt_get_result($stmt_promo);
        if (!mysqli_fetch_assoc($result_promo)) {
            echo "<script>alert('Promo ID tidak valid.'); window.location.href='../views/form_beli.php?id=$itemID';</script>";
            exit();
        }
        mysqli_stmt_close($stmt_promo);
    }

    $query_nominal = "SELECT harga FROM nominal WHERE id = ?";
    $stmt_nominal = mysqli_prepare($koneksi, $query_nominal);
    mysqli_stmt_bind_param($stmt_nominal, "i", $nominalID);
    mysqli_stmt_execute($stmt_nominal);
    $result_nominal = mysqli_stmt_get_result($stmt_nominal);
    $row_nominal = mysqli_fetch_assoc($result_nominal);
    $harga_nominal = $row_nominal['harga'];
    mysqli_stmt_close($stmt_nominal);
    var_dump($harga_nominal);
        $totalBefore = $harga_nominal * $jumlahBeli;

        // Default nilai $diskonPromo adalah 0
        $diskonPromo = 0;
        // Jika ada promoID, ambil nilai persen dari tabel promo
        if ($promoID) {
            $query_promo = "SELECT persen FROM promo WHERE id = ?";
            $stmt_promo = mysqli_prepare($koneksi, $query_promo);
            mysqli_stmt_bind_param($stmt_promo, "i", $promoID);
            mysqli_stmt_execute($stmt_promo);
            $result_promo = mysqli_stmt_get_result($stmt_promo);
            if ($row_promo = mysqli_fetch_assoc($result_promo)) {
                $persen = $row_promo['persen'];
                $diskonPromo = ($persen / 100) * $totalBefore;
            }
            mysqli_stmt_close($stmt_promo);
        }

        $totalBayar = $totalBefore - $diskonPromo;
        $tanggal = date('Y-m-d H:i:s');
        $status = 'pending';
        if ($promoID) {
            $sql = "INSERT INTO databeli (itemID, nominalID, dataAkun, jumlahBeli, jenisBayar, promoID, kontak) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "iisisis", $itemID, $nominalID, $dataAkun, $jumlahBeli, $jenisBayar, $promoID, $kontak);
        } else {
            $sql = "INSERT INTO databeli (itemID, nominalID, dataAkun, jumlahBeli, jenisBayar, kontak) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "iisiss", $itemID, $nominalID, $dataAkun, $jumlahBeli, $jenisBayar, $kontak);
        }
        if (mysqli_stmt_execute($stmt)) {
            $dataBeliID = mysqli_insert_id($koneksi); // Mendapatkan nilai dataBeliID dari insert terakhir

            $sql2 = "INSERT INTO transaksi (noInvoice, userID, dataBeliID, itemID, totalBayar, tanggal, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($koneksi, $sql2);
            mysqli_stmt_bind_param($stmt2, "siiisss", $transaksiID, $userID, $dataBeliID, $itemID, $totalBayar, $tanggal, $status);

            if (mysqli_stmt_execute($stmt2)) {
                echo "<script>alert('Pembelian berhasil!'); window.location.href='/views/user/cekTransaksi.php?noInvoice=$transaksiID&itemID=$itemID&dataBeliID=$dataBeliID';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat melakukan pembelian'); window.location.href='../views/form_beli.php?id=$itemID';</script>";
            }

            mysqli_stmt_close($stmt2);
        } else {
            echo "<script>alert('Terjadi kesalahan saat melakukan pembelian'); window.location.href='../views/form_beli.php?id=$itemID';</script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
}

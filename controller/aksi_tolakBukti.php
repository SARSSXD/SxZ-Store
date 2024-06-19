<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['noInvoice'])) {
        $noInvoice = $_POST['noInvoice'];

        // Update status transaksi menjadi "successful"
        $updateQuery = "UPDATE transaksi SET status = 'cancelled' WHERE noInvoice = ?";
        $stmt = $koneksi->prepare($updateQuery);
        $stmt->bind_param("s", $noInvoice);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Status transaksi berhasil diubah menjadi cancelled.";
        } else {
            $_SESSION['error_message'] = "Gagal mengubah status transaksi.";
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "No Invoice tidak ditemukan.";
    }
} else {
    $_SESSION['error_message'] = "Metode permintaan tidak valid.";
}

header("Location: /views/admin/transaksi.php");
exit();

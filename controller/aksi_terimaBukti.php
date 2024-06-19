<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['noInvoice'])) {
        $noInvoice = $_POST['noInvoice'];

        // Update status transaksi menjadi "successful"
        $updateQuery = "UPDATE transaksi SET status = 'successful' WHERE noInvoice = ?";
        $stmt = $koneksi->prepare($updateQuery);
        $stmt->bind_param("s", $noInvoice);

        if ($stmt->execute()) {
            // Ambil totalBayar dari transaksi yang telah diupdate
            $queryTotalBayar = "SELECT totalBayar FROM transaksi WHERE noInvoice = ?";
            $stmtTotalBayar = $koneksi->prepare($queryTotalBayar);
            $stmtTotalBayar->bind_param("s", $noInvoice);
            $stmtTotalBayar->execute();
            $resultTotalBayar = $stmtTotalBayar->get_result();
            $rowTotalBayar = $resultTotalBayar->fetch_assoc();
            $totalBayarTransaksi = $rowTotalBayar['totalBayar'];

            // Update totalBayar di tabel leaderboard
            $updateLeaderboardQuery = "UPDATE leaderboard 
                                       SET totalBeli = totalBeli + ? 
                                       WHERE userID IN (SELECT userID FROM transaksi WHERE noInvoice = ?)";
            $stmtLeaderboard = $koneksi->prepare($updateLeaderboardQuery);
            $stmtLeaderboard->bind_param("ss", $totalBayarTransaksi, $noInvoice);
            $stmtLeaderboard->execute();

            $_SESSION['success_message'] = "Status transaksi berhasil diubah menjadi successful.";
        } else {
            $_SESSION['error_message'] = "Gagal mengubah status transaksi.";
        }

        $stmt->close();
        $stmtTotalBayar->close();
        $stmtLeaderboard->close();
    } else {
        $_SESSION['error_message'] = "No Invoice tidak ditemukan.";
    }
} else {
    $_SESSION['error_message'] = "Metode permintaan tidak valid.";
}

header("Location: /views/admin/transaksi.php");
exit();

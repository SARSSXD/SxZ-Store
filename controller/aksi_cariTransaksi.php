<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/dbConfig.php';

// Check if the form has been submitted
if (isset($_POST['noInvoiceCari'])) {
    $noInvoiceCari = $_POST['noInvoiceCari'];

    // Search for itemID and dataBeliID based on noInvoice
    $transaksi = query("SELECT * FROM transaksi WHERE noInvoice = '$noInvoiceCari'");

    if (!empty($transaksi)) {
        $t = $transaksi[0];
        $itemIDCari = $t['itemID'];
        $dataBeliIDCari = $t['dataBeliID'];

        // Redirect to the appropriate URL with the found parameters
        header("Location: /../../views/user/cekTransaksi.php?noInvoice=$noInvoiceCari&itemID=$itemIDCari&dataBeliID=$dataBeliIDCari");
        exit();
    } else {
        $error = "No invoice found.";
        // Redirect back to the form with an error message
        header("Location: /views/user/cekTransaksi.php?error=$error");
        exit();
    }
} else {
    // If the form is not submitted, redirect back to the form
    header("Location: /views/user/cekTransaksi.php");
    exit();
}

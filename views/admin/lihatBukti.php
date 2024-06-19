<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$noInvoice = $_GET['noInvoice'];
$bukti = query("SELECT * FROM bukti WHERE transaksiID = '$noInvoice'");
$transaksi = query("SELECT * FROM transaksi WHERE noInvoice = '$noInvoice'");
$user = [];
if (!empty($bukti)) {
    if (!empty($bukti)) {
        $userID = $bukti[0]['userID'];
        if ($userID !== null) {
            $user = query("SELECT * FROM users WHERE id = $userID");
        } else {
            // Lakukan penanganan jika $userID adalah null
            $user = []; // Atau lakukan penanganan lainnya sesuai kebutuhan
        }
    }
}

?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <?php if (empty($bukti) || empty($transaksi)) : ?>
                <h1 colspan="5" class="text-center">Pembeli belum upload bukti pembayaran</h1>
            <?php else : ?>
                <?php $b = $bukti[0];
                $t = $transaksi[0] ?>
                <h1>Bukti Pembayaran</h1>
                <hr>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <img src="/assets/img/bukti/<?php echo htmlspecialchars($b['buktiBayar']); ?>" alt="" class="img-fluid img-thumbnail mb-3" id="image-preview">
                                <a href="/assets/img/bukti/<?php echo htmlspecialchars($b['buktiBayar']); ?>" download>
                                    <button class="btn btn-primary mt-3 float-right">Unduh</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <h3>Detail Bukti</h3>
                                <hr>
                                <?php if (!empty($user)) { ?>
                                    <h4>Nama Pembeli : <?php echo htmlspecialchars($user[0]['name']); ?></h4>
                                <?php } ?>
                                <h4>Jumlah Pembayaran : Rp <?php echo number_format(htmlspecialchars($b['amount']), 2, ',', '.'); ?></h4>
                                <h4>Tanggal Pembayaran : <?php echo htmlspecialchars($b['tanggalBayar']); ?></h4>
                                <a href="/views/admin/transaksi.php" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </main>

    <script>
        const imgInput = document.getElementById('image')
        const imgPreview = document.getElementById('image-preview')

        imgInput.onchange = evt => {
            const [file] = imgInput.files
            if (file) {
                imgPreview.src = URL.createObjectURL(file)
            }
        }
    </script>
</body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$transaksi = query("SELECT * FROM transaksi");
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Transaksi</h1>
            <hr>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card-secondary">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table align-middle text-center">
                                    <thead>
                                        <tr>
                                            <th>No. Invoice</th>
                                            <th>User ID</th>
                                            <th>Data Beli ID</th>
                                            <th>Item ID</th>
                                            <th>Total Bayar</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Bukti Bayar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($transaksi)) {
                                        ?>
                                            <tr>
                                                <td colspan="9" class="text-center">Data Transaksi Tidak Tersedia</td>
                                            </tr>
                                            <?php
                                        } else {
                                            foreach ($transaksi as $t) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($t['noInvoice']); ?></td>
                                                    <td>
                                                        <?php
                                                        if (empty($t['userID'])) {
                                                            echo 'guest';
                                                        } else {
                                                            echo htmlspecialchars($t['userID']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($t['dataBeliID']); ?></td>
                                                    <td><?php echo htmlspecialchars($t['itemID']); ?></td>
                                                    <td><?php echo htmlspecialchars($t['totalBayar']); ?></td>
                                                    <td><?php echo htmlspecialchars($t['tanggal']); ?></td>
                                                    <td><?php echo htmlspecialchars($t['status']); ?></td>
                                                    <td>
                                                        <?php if (!empty($t['buktiID'])) :
                                                            $BuktiID = $t['buktiID'];
                                                            $bukti = query("SELECT * FROM bukti WHERE id = $BuktiID");
                                                            $b = $bukti[0]; ?>
                                                            <img src="/assets/img/bukti/<?php echo htmlspecialchars($b['buktiBayar']); ?>" alt="" width="100" class="rounded">
                                                        <?php else : ?>
                                                            <span class="text-muted">Tidak ada bukti bayar</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="lihatBukti.php?noInvoice=<?php echo htmlspecialchars($t['noInvoice']); ?>" class="btn btn-sm btn-primary mb-3">Lihat Bukti</a>
                                                        <form class="d-inline-block" action="/controller/aksi_terimaBukti.php" method="POST">
                                                            <input type="hidden" name="noInvoice" value="<?php echo htmlspecialchars($t['noInvoice']); ?>">
                                                            <button class="btn btn-sm btn-success mb-3">Terima</button>
                                                        </form>
                                                        <form class="d-inline-block" action="/controller/aksi_tolakBukti.php" method="POST">
                                                            <input type="hidden" name="noInvoice" value="<?php echo htmlspecialchars($t['noInvoice']); ?>">
                                                            <button class="btn btn-sm btn-danger mb-3">Tolak</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
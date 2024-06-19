<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$noInvoice = $_GET['noInvoice'];
$itemID = $_GET['itemID'];
$dataBeliID = $_GET['dataBeliID'];
$userID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

// Cek apakah buktiID ada di tabel transaksi
$query = "SELECT buktiID FROM transaksi WHERE noInvoice = '$noInvoice'";
$result = mysqli_query($koneksi, $query);
$buktiID = mysqli_fetch_assoc($result)['buktiID'] ?? null;

if (!empty($buktiID)) {
    $buktiQuery = "SELECT * FROM bukti WHERE id = '$buktiID'";
    $buktiResult = mysqli_query($koneksi, $buktiQuery);
    $bukti = mysqli_fetch_assoc($buktiResult);
}

$formAction = $buktiID ? '/controller/aksi_editBukti.php' : '/controller/aksi_tambahBukti.php';
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Upload Bukti Pembayaran | <?php echo htmlspecialchars($noInvoice); ?></h1>
            <hr>
            <form class="row" action="<?php echo htmlspecialchars($formAction); ?>" method="POST" enctype="multipart/form-data">
                <?php if (!empty($buktiID) && !empty($bukti)) : ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <img src="/assets/img/bukti/<?php echo htmlspecialchars($bukti['buktiBayar']); ?>" alt="" class="img-fluid img-thumbnail mb-3" id="image-preview">
                                <div>
                                    <label for="image" class="form-label">Gambar Bukti</label>
                                    <input class="form-control-addon-secondary" name="image" type="file" id="image" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <h3>Detail Pembayaran</h3>
                                <hr>
                                <div class="mb-3">
                                    <label for="amount">Jumlah</label>
                                    <input type="number" placeholder="<?php echo $bukti['amount'];?>" name="amount" id="amount" class="form-control" required>
                                </div>
                                <input type="hidden" name="userID" id="userID" value="<?php echo htmlspecialchars($userID); ?>">
                                <input type="hidden" name="transaksiID" id="transaksiID" value="<?php echo htmlspecialchars($noInvoice); ?>">
                                <input type="hidden" name="itemID" id="itemID" value="<?php echo htmlspecialchars($itemID); ?>">
                                <input type="hidden" name="dataBeliID" id="dataBeliID" value="<?php echo htmlspecialchars($dataBeliID); ?>">
                                <input type="hidden" name="buktiID" id="buktiID" value="<?php echo htmlspecialchars($buktiID); ?>">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <img src="/assets/img/product.jpg" alt="" class="img-fluid img-thumbnail mb-3" id="image-preview">
                                <div>
                                    <label for="image" class="form-label">Gambar Bukti</label>
                                    <input class="form-control-addon-secondary" name="image" type="file" id="image" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <h3>Detail Pembayaran</h3>
                                <hr>
                                <div class="mb-3">
                                    <label for="amount">Jumlah</label>
                                    <input type="number" name="amount" id="amount" class="form-control" required>
                                </div>
                                <input type="hidden" name="userID" id="userID" value="<?php echo htmlspecialchars($userID); ?>">
                                <input type="hidden" name="transaksiID" id="transaksiID" value="<?php echo htmlspecialchars($noInvoice); ?>">
                                <input type="hidden" name="itemID" id="itemID" value="<?php echo htmlspecialchars($itemID); ?>">
                                <input type="hidden" name="dataBeliID" id="dataBeliID" value="<?php echo htmlspecialchars($dataBeliID); ?>">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <script>
        const imgInput = document.getElementById('image');
        const imgPreview = document.getElementById('image-preview');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
            }
        }
    </script>
</body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../main-layouts.php';
require_once __DIR__ .'/../../models/dbConfig.php';
require_once __DIR__ . '/../../models/connection.php';

$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_logged_in = isset($_SESSION['user']);
$userID = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
$nominal = query("SELECT * FROM nominal WHERE itemID = $item_id ORDER BY harga ASC");
$item = query("SELECT * FROM item WHERE id= $item_id");
$promo = query("SELECT * FROM promo");
$review = query("SELECT * FROM review");

function formatDate($date, $format = 'd-m-y')
{
    return date($format, strtotime($date));
}
function sensorKontak($kontak)
{
    // Mengganti bagian tengah nomor kontak dengan bintang
    return preg_replace('/(\d{2})(\d+)(\d{2})/', '$1' . str_repeat('****', strlen('$2')) . '$3', $kontak);
}
function printStars($nilai)
{
    $stars = '';
    for ($i = 0; $i < $nilai; $i++) {
        $stars .= '<span style="color: #f7d106;">&#9733;</span>'; // Tambahkan tanda bintang Unicode
    }
    return $stars;
}
$totalNilai = 0;
$totalReview = count($review);
foreach ($review as $r) {
    $totalNilai += $r['nilai'];
}
$nilaiRataRata = $totalNilai / $totalReview;
?>

<head>
    <style>
        .border-primary {
            border-color: #007bff !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid bg-dark">
        <form class="row bg-dark text-light" action="/controller/aksi_beliForm.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <?php foreach ($item as $i) : ?>
                    <div class="card text-white bg-dark rounded" style="width: 100%; height: 100%;">
                        <img class="card-img-top" src="/assets/img/card/<?php echo $i['gambar']; ?>" alt="Card image cap">
                        <div class="card-body">
                            <h6 class="card-title text-white">Beli Top Up, Apps, dan Joki dengan harga paling murah, aman, cepat, dan terpercaya hanya di SxZ Store.</h6>
                            <h6>Cara Topup :</h6>
                            <ul>
                                <li>Pilih Nominal</li>
                                <li>Masukkan Data Akun</li>
                                <li>Tentukan Jumlah Pembelian</li>
                                <li>Pilih Pembayaran</li>
                                <li>Masukkan Kode Promo (jika ada)</li>
                                <li>Isi Detail Kontak</li>
                                <li>Klik Pesan Sekarang dan lakukan Pembayaran</li>
                                <li>Selesai</li>
                            </ul>
                            <div class="border"></div>
                            <div class="text-center">
                                <?php if (!empty($review)) : ?>
                                    <h2>Review Pengguna</h2>
                                    <h2><span style="color: #f7d106;">&#9733;</span><?php echo $nilaiRataRata; ?>/5</h2>
                                    <h6>Dari <?php echo $totalReview ?> ulasan</h6>
                                    <div class="row">
                                        <?php foreach ($review as $r) :
                                            $noInvoice = $r['noInvoice'];
                                            $transaksi = query("SELECT * FROM transaksi WHERE noInvoice = '$noInvoice'");
                                            $dataBeliID = $transaksi[0]['dataBeliID'];
                                            $dataBeli = query("SELECT * FROM databeli WHERE id = '$dataBeliID'");
                                            $db = $dataBeli[0];
                                        ?>
                                            <div class="col-md-6">
                                                <div class="card bg-dark text-white mb-3" style="width: 100%; height: 100%;">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Nilai: <?php echo printStars($r['nilai']); ?></h5>
                                                        <p><?php echo sensorKontak($db['kontak']); ?></p>
                                                        <p class="card-text">Komentar: <?php echo $r['komentar']; ?></p>
                                                        <p class="card-text">Tanggal Review: <?php echo formatDate($r['tglKomen']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-12 col-md">
                <div class="card bg-dark text-light">
                    <div class="card-body rounded">
                        <h4>Pilih Nominal</h4>
                        <div class="row mt-3">
                            <?php foreach ($nominal as $nm) : ?>
                                <div class="col-6 col-md-4 col-lg-4 mb-3 bg-dark text-light">
                                    <div class="card text-white bg-dark border-secondary nominal-card" style="width: 100%; cursor: pointer;" onclick="selectNominal(<?php echo $nm['id']; ?>)">
                                        <div class="row">
                                            <div class="col-8">
                                                <h6 class="card-title text-white"><?php echo $nm['nominal']; ?></h6>
                                                <h6 class="card-title text-white">Rp <?php echo number_format(htmlspecialchars($nm['harga']), 2, ',', '.'); ?></h6>
                                            </div>
                                            <div class="col-4">
                                                <img class="card-img-top" src="../../assets/img/nominal/<?php echo $nm['gambar']; ?>" alt="Card image cap">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="selected_nominal_id" id="selected_nominal_id" value="">
                        <h4>Masukkan Data Akun</h4>
                        <div class="mb-3">
                            <label for="">Contoh : ID(Server) > 123456789 (12345)</label>
                            <input type="text" name="dataAkun" id="dataAkun" class="form-control" required>
                        </div>
                        <h4>Masukkan Jumlah Pembelian</h4>
                        <div class="mb-3">
                            <input type="number" name="jumlahBeli" id="jumlahBeli" class="form-control" required>
                        </div>
                        <h4>Pilih Pembayaran</h4>
                        <div class="mb-3">
                            <select name="jenisBayar" id="jenisBayar" class="form-control" required>
                                <option value="">Pilih Pembayaran</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Convenience Store">Convenience Store</option>
                            </select>
                        </div>
                        <h4>Kode Promo</h4>
                        <div class="mb-3">
                            <select class="form-control" name="promo" id="promo">
                                <option value="">Pilih Promo</option>
                                <?php if ($user_logged_in) : ?>
                                    <?php foreach ($promo as $p) : ?>
                                        <option value="<?php echo $p['id'] ?>"><?php echo $p['nama'] ?> | Min.Blj Rp<?php echo number_format(htmlspecialchars($p['minimBeli']), 2, ',', '.'); ?> | Hingga <?php echo formatDate($p['kadaluwarsa'], 'd-m-y'); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <h4>Detail Kontak</h4>
                        <div class="mb-3">
                            <input type="text" placeholder="085159690099" name="kontak" id="kontak" class="form-control" required>
                            <label for="">*akan dihubungi jika ada masalah</label>
                        </div>
                        <input type="hidden" name="itemID" value="<?php echo $item_id; ?>">
                        <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                        <button class="btn btn-primary">Beli</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        function selectNominal(nominalId) {
            // Reset border style for all cards
            var cards = document.querySelectorAll('.nominal-card');
            cards.forEach(function(card) {
                card.classList.remove('border-primary');
            });

            // Set border style for the selected card
            var selectedCard = document.querySelector('[onclick="selectNominal(' + nominalId + ')"]');
            selectedCard.classList.add('border-primary');

            // Set the selected nominal ID to the hidden input field
            document.getElementById('selected_nominal_id').value = nominalId;
        }

        function validateForm() {
            var selectedNominalId = document.getElementById('selected_nominal_id').value;
            if (!selectedNominalId) {
                alert('Harap pilih nominal terlebih dahulu.');
                document.getElementById('selected_nominal_id').scrollIntoView();
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</body>
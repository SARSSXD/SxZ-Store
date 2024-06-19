<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php';

$user_logged_in = isset($_SESSION['user']);
$noInvoice = isset($_GET['noInvoice']) ? $_GET['noInvoice'] : null;
$itemID = isset($_GET['itemID']) ? $_GET['itemID'] : null;
$dataBeliID = isset($_GET['dataBeliID']) ? $_GET['dataBeliID'] : null;

if ($noInvoice && $itemID && $dataBeliID) {
    $transaksi = query("SELECT * FROM transaksi WHERE noInvoice = '$noInvoice'");
    $item = query("SELECT * FROM item WHERE id = $itemID");
    $dataBeli = query("SELECT * FROM databeli WHERE id = $dataBeliID");
    $nominal = [];
    if (!empty($dataBeli)) {
        $nominalID = $dataBeli[0]['nominalID'];
        $nominal = query("SELECT * FROM nominal WHERE id = $nominalID");
    }
    $bukti = query("SELECT * FROM bukti WHERE transaksiID = '$noInvoice'");
} else {
    $transaksi = [];
    $item = [];
    $dataBeli = [];
    $nominal = [];
    $bukti = [];
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .star-rating {
            direction: rtl;
            display: inline-block;
            padding: 20px;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input[type="radio"]:checked~label {
            color: #f7d106;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #f7d106;
        }

        @media print {
            body {
                color: #000 !important;
            }

            .btn {
                display: none;
            }

            .text-white {
                color: #000 !important;
            }

            .bg-dark {
                background-color: #fff !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-dark text-white mb-5">
    <div class="container">
        <?php if ($noInvoice && $itemID && $dataBeliID) : ?>
            <?php if (!empty($transaksi) && !empty($item) && !empty($dataBeli) && !empty($nominal)) : ?>
                <?php $t = $transaksi[0];
                $i = $item[0];
                $db = $dataBeli[0];
                $nm = $nominal[0]; ?>
                <?php if ($t['status'] == "successful") : ?>
                    <h6>Terima Kasihhhhhhhhhh!</h6>
                    <h1>Sudah Percaya Pada Kami. <button onclick="window.print();" class="btn btn-primary float-right">Cetak</button>
                    </h1>
                <?php else : ?>
                    <h6>Terima Kasihhhhhhhhhh!</h6>
                    <h1>Harap Lengkapi Pembayaran.</h1>
                    <h5>Pesanan kamu <span class="text-warning"><?= htmlspecialchars($noInvoice) ?></span> menunggu pembayaran sebelum dikirim</h5>
                    <h3><span id="countdown-timer" class="bg-danger rounded py-1 text-center"></span></h3>
                <?php endif; ?>
                <div class="border mt-3"></div>
                <div class="row mt-3">
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <h4>Detail Item</h4>
                        <div class="text-center">
                            <img class="img-fluid rounded mb-3" src="/assets/img/card/<?php echo $i['gambar']; ?>" alt="" style="max-width: 200px;">
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <h5 class="d-flex justify-content-between"><span>Nama Game</span><span>:</span></h5>
                                <h5 class="d-flex justify-content-between"><span>Nominal</span><span>:</span></h5>
                                <h5 class="d-flex justify-content-between"><span>Data Akun</span><span>:</span></h5>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <h5><?php echo htmlspecialchars($i['nama']); ?></h5>
                                <h5><?php echo htmlspecialchars($nm['nominal']); ?></h5>
                                <h5><?php echo htmlspecialchars($db['dataAkun']); ?></h5>
                            </div>
                        </div>
                    </div>
                    <span class="border"></span>
                    <div class="col-12 col-md mt-3 mt-md-0">
                        <h4>Metode Pembayaran</h4>
                        <h5><?php echo htmlspecialchars($db['jenisBayar']) ?></h5>
                        <div class="border mt-3 mb-3"></div>
                        <div class="row">
                            <div class="col-5">
                                <h5 class="d-flex justify-content-between"><span>Nomor Invoice</span><span>:</span></h5>
                                <h5 class="d-flex justify-content-between"><span>Total Pembayaran</span><span>:</span></h5>
                                <h5 class="d-flex justify-content-between"><span>Status Transaksi</span><span>:</span></h5>
                                <div class="text-center mt-3">
                                    <?php if ($t['status'] == "pending") : ?>
                                        <div class="text-center mt-3">
                                            <img class="img-fluid img-thumbnail" src="/assets/img/QRIS.png" alt="" style="max-height: 200px;">
                                            <a href="/assets/img/QRIS.png" download>
                                                <button class="btn btn-primary mt-3" style="width: 200px;">Unduh QRIS</button>
                                            </a>
                                        </div>
                                    <?php elseif ($t['status'] == "successful") : ?>
                                        <!-- form review -->
                                        <form action="/controller/aksi_review.php" method="POST" class="card bg-dark text-light p-3 mt-3 no-print">
                                            <h3>REVIEW</h3>
                                            <p>Terima kasih telah bertransaksi dengan kami. Silakan berikan ulasan Anda.</p>
                                            <div class="star-rating text-center">
                                                <input type="radio" id="5-stars" name="rating" value="5" />
                                                <label for="5-stars" class="star">&#9733;</label>
                                                <input type="radio" id="4-stars" name="rating" value="4" />
                                                <label for="4-stars" class="star">&#9733;</label>
                                                <input type="radio" id="3-stars" name="rating" value="3" />
                                                <label for="3-stars" class="star">&#9733;</label>
                                                <input type="radio" id="2-stars" name="rating" value="2" />
                                                <label for="2-stars" class="star">&#9733;</label>
                                                <input type="radio" id="1-stars" name="rating" value="1" />
                                                <label for="1-stars" class="star">&#9733;</label>
                                            </div>
                                            <label for="komen" class="text-left">Deskripsi</label>
                                            <textarea name="komentar" id="komentar" class="form-control" rows="3"></textarea>
                                            <input type="hidden" id="noInvoice" name="noInvoice" value="<?php echo $noInvoice ?>">
                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-5">
                                <p><?php echo htmlspecialchars($t['noInvoice']); ?></p>
                                <p>Rp <?php echo number_format(htmlspecialchars($t['totalBayar']), 2, ',', '.'); ?></p>
                                <p><span class="<?php echo $t['status'] == 'pending' ? 'bg-warning' : '';
                                                echo $t['status'] == 'cancelled' ? 'bg-danger' : '';
                                                echo $t['status'] == 'successful' ? 'bg-success' : ''; ?> rounded p-1"><?php echo htmlspecialchars($t['status']); ?></span></p>
                                <?php if (!empty($bukti)) :  $b = $bukti[0]; ?>
                                    <img src="/assets/img/bukti/<?php echo $b['buktiBayar']; ?>" alt="" class="img-fluid img-thumbnail mb-3 no-print" id="image-preview" style="max-height: 200px;">
                                <?php else : ?>
                                    <img src="/assets/img/product.jpg" alt="" class="img-fluid img-thumbnail mb-3 no-print" id="image-preview" style="max-height: 200px;">
                                <?php endif; ?>
                                <div>
                                    <?php if ($t['status'] == "pending") : ?>
                                        <div>
                                            <a href="/views/user/tambahBukti.php?noInvoice=<?php echo $noInvoice ?>&itemID=<?php echo $itemID ?>&dataBeliID=<?php echo $dataBeliID ?>" class="btn btn-primary mt3">Upload Bukti Pembayaran</a>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border my-5"></div>
            <?php else : ?>
                <p>Item atau Transaksi tidak ditemukan.</p>
            <?php endif; ?>
        <?php else : ?>
            <form method="POST" action="/controller/aksi_cariTransaksi.php">
                <div class="form-group">
                    <label for="noInvoiceCari">Nomor Invoice:</label>
                    <input type="text" class="form-control" id="noInvoiceCari" name="noInvoiceCari" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Cari</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Mengambil waktu transaksi dari PHP
        var transaksiTime = new Date("<?php echo isset($t['tanggal']) ? $t['tanggal'] : ''; ?>").getTime();
        var countdownTime = transaksiTime + 3 * 60 * 60 * 1000; // Menambahkan 3 jam

        // Update hitung mundur setiap 1 detik
        var countdownInterval = setInterval(function() {
            var now = new Date().getTime();
            var distance = countdownTime - now;

            // Perhitungan waktu untuk hari, jam, menit dan detik
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Menampilkan hasil dalam elemen dengan id="countdown-timer"
            document.getElementById("countdown-timer").innerHTML = hours + " Jam " +
                minutes + " Menit " + seconds + " Detik ";

            // Jika hitungan mundur selesai, tampilkan teks
            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById("countdown-timer").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>
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
    <script>
        document.querySelectorAll('.star-rating label').forEach(label => {
            label.addEventListener('click', () => {
                let siblings = [...label.parentNode.children].reverse();
                let labelIndex = siblings.indexOf(label);
                siblings.forEach((el, index) => {
                    if (index <= labelIndex) {
                        el.style.color = '#f7d106';
                    } else {
                        el.style.color = '#ddd';
                    }
                });
            });
        });
    </script>
</body>
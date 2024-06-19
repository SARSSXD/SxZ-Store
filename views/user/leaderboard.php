<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../main-layouts.php'; // Gunakan path absolut relatif terhadap file ini
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

// Ambil data leaderboard yang diurutkan berdasarkan totalBeli secara descending (paling banyak ke paling sedikit) dan sertakan nama pengguna dari tabel users
$leaderboard = query("
    SELECT leaderboard.totalBeli, users.name
    FROM leaderboard
    JOIN users ON leaderboard.userID = users.id
    ORDER BY leaderboard.totalBeli DESC
    LIMIT 10
");
?>

<body class="bg-dark">
    <div class="container">
        <h5 class="text-center text-info mt-5">LEADERBOARD</h5>
        <h1 class="text-center text-white">TOP 10 Pembelian Terbanyak di sarStore</h1>
        <p class="text-center text-white">Berikut ini adalah daftar 10 pembelian terbanyak yang dilakukan oleh pelanggan kami. Data ini diambil dari sistem kami dan selalu diperbaharui.</p>
        <div class="border mt-3"></div>

        <!-- Tabel untuk menampilkan data leaderboard -->
        <table class="table table-striped table-dark mt-4">
            <!-- <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Total Beli</th>
                </tr>
            </thead> -->
            <tbody>
                <?php foreach ($leaderboard as $index => $data) : ?>
                    <tr>
                        <!-- <th scope="row"><?= $index + 1 ?></th> -->
                        <td><?= $index + 1 ?>. <?= $data['name'] ?></td>
                        <td>Rp <?php echo number_format(htmlspecialchars($data['totalBeli']), 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

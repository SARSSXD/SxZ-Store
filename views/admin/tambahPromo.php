<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $minimBeli = htmlspecialchars($_POST['minimBeli']);
    $kadaluwarsa = htmlspecialchars($_POST['kadaluwarsa']);
    $persen = htmlspecialchars($_POST['persen']);

    $sql = "INSERT INTO promo (nama, minimBeli, kadaluwarsa, persen) VALUES (?, ?, ?,?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'sdsd', $nama, $minimBeli, $kadaluwarsa, $persen);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Promo berhasil ditambahkan'); window.location.href='/views/admin/promo.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan promo'); window.location.href='/views/admin/tambahPromo.php';</script>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Tambah Promo</h1>
            <hr>
            <form action="/controller/aksi_tambahPromo.php" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Promo</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="minimBeli" class="form-label">Minimum Pembelian</label>
                    <input type="number" name="minimBeli" id="minimBeli" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="minimBeli" class="form-label">Persentase Diskon</label>
                    <input type="number" name="persen" id="persen" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kadaluwarsa" class="form-label">Tanggal Kadaluwarsa</label>
                    <input type="date" name="kadaluwarsa" id="kadaluwarsa" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Promo</button>
            </form>
        </div>
    </main>
</body>
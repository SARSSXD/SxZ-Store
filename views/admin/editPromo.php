<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php'; // Gunakan path absolut relatif terhadap file ini
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$promo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$promo = query("SELECT * FROM promo WHERE id = $promo_id");
$promo = $promo[0];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $minimBeli = htmlspecialchars($_POST['minimBeli']);
    $kadaluwarsa = htmlspecialchars($_POST['kadaluwarsa']);

    $sql = "UPDATE promo SET nama = ?, minimBeli = ?, kadaluwarsa = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'sdsi', $nama, $minimBeli, $kadaluwarsa, $promo_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Promo berhasil diubah'); window.location.href='promo.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengubah promo'); window.location.href='editPromo.php?id=$promo_id';</script>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Edit Promo</h1>
            <hr>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Promo</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($promo['nama']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="minimBeli" class="form-label">Minimum Pembelian</label>
                    <input type="number" name="minimBeli" id="minimBeli" class="form-control" value="<?php echo htmlspecialchars($promo['minimBeli']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kadaluwarsa" class="form-label">Tanggal Kadaluwarsa</label>
                    <input type="date" name="kadaluwarsa" id="kadaluwarsa" class="form-control" value="<?php echo htmlspecialchars($promo['kadaluwarsa']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Promo</button>
            </form>
        </div>
    </main>
</body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../main-layouts.php'; // Gunakan path absolut relatif terhadap file ini
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$produk = query("SELECT * FROM item")
?>

<body class="bg-dark text-white">
    <div class="container">
        <h1>Pengajuan Laporan</h1>
        <div class="border mb-3"></div>
        <form method="POST" action="/controller/aksi_report.php">
            <div class="form-group">
                <label for="noInvoiceCari">Nama Anda:</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="price">Jenis Laporan</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="">Pilih Jenis</option>
                    <option value="Masalah Akun">Masalah Akun</option>
                    <option value="Masalah Transaksi">Masalah Transaksi</option>
                    <option value="Pembuatan Website">Pembuatan Website</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="">Kontak Anda</label>
                <input type="text" placeholder="085159690099" name="kontak" id="kontak" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
</body>
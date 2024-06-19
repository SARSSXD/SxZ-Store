<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php';
require_once __DIR__ . '/../../models/connection.php'; // Gunakan path absolut relatif terhadap file ini

// Ambil ID item dari URL
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data item dari database
$sql = "SELECT * FROM item WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    echo "Item tidak ditemukan!";
    exit;
}

mysqli_stmt_close($stmt);
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Edit Product</h1>
            <hr>
            <form class="row" action="../../controller/aksi_editItem.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card bg-dark text-light">
                        <div class="card-body">
                            <img src="../../assets/img/card/<?php echo htmlspecialchars($item['gambar']); ?>" alt="Item Image" class="img-fluid img-thumbnail mb-3" id="image-preview">
                            <div>
                                <label for="image" class="form-label">Gambar Item</label>
                                <input class="form-control-addon-secondary" name="image" type="file" id="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md">
                    <div class="card bg-dark text-light">
                        <div class="card-body">
                            <h3>Detail Item</h3>
                            <hr>
                            <div class="mb-3">
                                <label for="name">Nama Item</label>
                                <input type="text" name="product_name" id="name" class="form-control" value="<?php echo htmlspecialchars($item['nama']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori">Kategori</label>
                                <select name="kategori" id="kategori" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Topup" <?php echo $item['kategori'] == 'Topup' ? 'selected' : ''; ?>>Top Up</option>
                                    <option value="Apps" <?php echo $item['kategori'] == 'Apps' ? 'selected' : ''; ?>>Apps</option>
                                    <option value="Joki" <?php echo $item['kategori'] == 'Joki' ? 'selected' : ''; ?>>Joki</option>
                                </select>
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
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
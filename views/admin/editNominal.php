<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php';
require_once __DIR__ . '/../../models/connection.php'; // Gunakan path absolut relatif terhadap file ini

// Ambil ID nominal dari URL
$nominal_ID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data nominal dari database
$sql = "SELECT * FROM nominal WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $nominal_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$nominal = mysqli_fetch_assoc($result);

if (!$nominal) {
    echo "Nominal tidak ditemukan!";
    exit;
}

mysqli_stmt_close($stmt);
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Edit Nominal</h1>
            <hr>
            <form class="row" action="../../controller/aksi_editNominal.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($nominal['id']); ?>">
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card bg-dark text-light">
                        <div class="card-body">
                            <img src="../../assets/img/nominal/<?php echo htmlspecialchars($nominal['gambar']); ?>" alt="Nominal Image" class="img-fluid img-thumbnail mb-3" id="image-preview">
                            <div>
                                <label for="image" class="form-label">Gambar Nominal</label>
                                <input class="form-control-addon-secondary" name="image" type="file" id="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md">
                    <div class="card bg-dark text-light">
                        <div class="card-body">
                            <h3>Detail Nominal | ID Item = <?php echo htmlspecialchars($nominal['itemID']); ?></h3>
                            <hr>
                            <div class="mb-3">
                                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($nominal['itemID']); ?>">
                                <label for="nominal">Nominal</label>
                                <input type="text" name="nominal" id="nominal" class="form-control" value="<?php echo htmlspecialchars($nominal['nominal']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" id="harga" class="form-control" value="<?php echo htmlspecialchars($nominal['harga']); ?>" required>
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
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
        };
    </script>
</body>
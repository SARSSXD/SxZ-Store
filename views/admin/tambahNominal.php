<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$item = query("SELECT * FROM item WHERE id = $item_id");
$nominals = query("SELECT * FROM nominal WHERE itemID = $item_id");

if (empty($item)) {
    echo "Item tidak ditemukan.";
    exit;
}
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>New Nominal</h1>
            <hr>
            <form class="row" action="../../controller/aksi_tambahNominal.php" method="POST" enctype="multipart/form-data">
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card bg-dark text-light">
                        <div class="card-body">
                            <img src="../../assets/img/product.jpg" alt="" class="img-fluid img-thumbnail mb-3" id="image-preview">
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
                            <h3>Detail Nominal | <?php echo htmlspecialchars($item[0]['nama']) ?></h3>
                            <hr>
                            <div class="mb-3">
                                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id) ?>">
                                <label for="nominal">Nominal</label>
                                <input type="text" name="nominal" id="nominal" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" id="harga" class="form-control" required>
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabel data nominal -->
            <h2 class="mt-5">Data Nominal</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nominal</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($nominals)) { ?>
                            <tr>
                                <td colspan="5" class="text-center">Data Nominal Tidak Tersedia</td>
                            </tr>
                            <?php } else {
                            foreach ($nominals as $n) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($n['id']); ?></td>
                                    <td><?php echo htmlspecialchars($n['nominal']); ?></td>
                                    <td>Rp <?php echo number_format(htmlspecialchars($n['harga']), 2, ',', '.'); ?></td>
                                    <td><img src="../../assets/img/nominal/<?php echo htmlspecialchars($n['gambar']); ?>" alt="" width="100" class="rounded"></td>
                                    <td>
                                        <a href="editNominal.php?id=<?php echo htmlspecialchars($n['id']); ?>" class="btn btn-sm btn-warning mb-3">Edit</a>
                                        <form class="d-inline-block delete-nominal" action="/controller/aksi_deleteNominal.php" method="POST" id="deleteForm_<?php echo htmlspecialchars($n['id']); ?>">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($n['id']); ?>">
                                            <button class="btn btn-sm btn-danger mb-3 deleteButton" data-nominal-id="<?php echo htmlspecialchars($n['id']); ?>">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
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
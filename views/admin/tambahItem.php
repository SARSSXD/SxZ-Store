<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini


$user = query("SELECT * FROM item");
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>New Item</h1>
            <hr>
            <form class="row" action="/controller/aksi_tambahItem.php" method="POST" enctype="multipart/form-data">
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card bg-dark text-light">
                        <div class="card-body">
                            <img src="../../assets/img/product.jpg" alt="" class="img-fluid img-thumbnail mb-3" id="image-preview">
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
                                <input type="text" name="product_name" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="price">Kategori</label>
                                <select name="kategori" id="kategori" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Topup">Top Up</option>
                                    <option value="Apps">Apps</option>
                                    <option value="Joki">Joki</option>
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
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
            <h1>Products</h1>
            <hr>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="mb-3">
                        <a href="tambahItem.php" class="btn btn-primary">Tambah</a>
                    </div>
                    <div class="card-secondary">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table align-middle">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th></th>
                                            <th>Nama Item</th>
                                            <th>Kategori</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($user)) {
                                        ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Data Item Tidak Tersedia</td>
                                            </tr>
                                            <?php
                                        } else {
                                            foreach ($user as $u) :
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($u['id']); ?></td>
                                                    <td><img src="../../assets/img/card/<?php echo htmlspecialchars($u['gambar']); ?>" alt="" width="100" class="rounded"></td>
                                                    <td><?php echo htmlspecialchars($u['nama']); ?></td>
                                                    <td><?php echo htmlspecialchars($u['kategori']); ?></td>
                                                    <td>
                                                        <a href="tambahNominal.php?id=<?php echo htmlspecialchars($u['id']); ?>" class="btn btn-sm btn-primary mb-3">Nominal</a>
                                                        <a href="editItem.php?id=<?php echo htmlspecialchars($u['id']); ?>" class="btn btn-sm btn-warning mb-3">Edit</a>
                                                        <form class="d-inline-block delete-product" action="/controller/aksi_deleteItem.php" method="POST" id="deleteForm_<?php echo htmlspecialchars($u['id']); ?>">
                                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($u['id']); ?>">
                                                            <button class="btn btn-sm btn-danger mb-3 deleteButton" data-product-id="<?php echo htmlspecialchars($u['id']); ?>">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            endforeach;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
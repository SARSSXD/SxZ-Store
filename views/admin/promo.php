<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../main-layouts.php'; // Gunakan path absolut relatif terhadap file ini
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$promos = query("SELECT * FROM promo");
?>

<body class="bg-dark">
    <main class="py-3 bg-dark text-light">
        <div class="container">
            <h1>Promo</h1>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div>
                        <a href="tambahPromo.php" class="btn btn-primary">Tambah Promo</a>
                    </div>
                    <div class="card-secondary">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table align-middle">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Promo</th>
                                            <th>Minimum Pembelian</th>
                                            <th>Tanggal Kadaluwarsa</th>
                                            <th>Persentase Diskon</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($promos)) {
                                        ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Data Promo Tidak Tersedia</td>
                                            </tr>
                                            <?php
                                        } else {
                                            foreach ($promos as $promo) :
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($promo['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($promo['nama']); ?></td>
                                                    <td><?php echo htmlspecialchars($promo['minimBeli']); ?></td>
                                                    <td><?php echo htmlspecialchars($promo['kadaluwarsa']); ?></td>
                                                    <td><?php echo htmlspecialchars($promo['persen']); ?>%</td>
                                                    <td>
                                                        <a href="editPromo.php?id=<?php echo htmlspecialchars($promo['id']); ?>" class="btn btn-sm btn-warning mb-3">Edit</a>
                                                        <form class="d-inline-block delete-promo" action="/controller/aksi_deletePromo.php" method="POST" id="deleteForm_<?php echo htmlspecialchars($promo['id']); ?>">
                                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($promo['id']); ?>">
                                                            <button class="btn btn-sm btn-danger mb-3 deleteButton" data-promo-id="<?php echo htmlspecialchars($promo['id']); ?>">
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

    <script>
        document.querySelectorAll('.deleteButton').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const promoId = this.getAttribute('data-promo-id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: 'Promo ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm_' + promoId).submit();
                    }
                })
            });
        });
    </script>
</body>
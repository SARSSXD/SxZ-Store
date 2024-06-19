<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../main-layouts.php'; // Gunakan path absolut relatif terhadap file ini
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$produk = query("SELECT * FROM item")
?>

<body class="bg-dark">
    <div class="content mt-3 bg-dark text-light">
        <div class="container text-center">
            <!-- User -->
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100 rounded-circle" src="/assets/img/2.png" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 rounded-circle" src="/assets/img/3.png" alt="Second slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <div class="mt-3 btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary" data-category="Topup">Top UP</button>
                <button type="button" class="btn btn-secondary" data-category="Joki">Jasa Joki</button>
                <button type="button" class="btn btn-secondary" data-category="Apps">Apps</button>
                <button type="button" class="btn btn-secondary" data-category="all">All</button>
            </div>
            <div class="row mt-3">
                <?php foreach ($produk as $pr) : ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-3 bg-dark text-light item-card" data-category="<?php echo $pr['kategori']; ?>">
                        <div class="card text-white bg-dark border-secondary" style="width: 100%; height: 100%;">
                            <a href="/views/user/formBeli.php?id=<?php echo $pr['id']; ?>">
                                <img class="card-img-top" src="/assets/img/card/<?php echo $pr['gambar']; ?>" alt="Card image cap" style="height:300px">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title text-white"><?php echo $pr['nama']; ?></h5>
                                <!-- <a href="/views/user/formBeli.php?id=<?php echo $pr['id']; ?>" class="btn btn-primary">Beli</a> -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.btn-group button').forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');
                document.querySelectorAll('.item-card').forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Use absolute path relative to this file

$review = query("SELECT * FROM review");
?>

<body class="bg-dark">
    <div class="content mt-3 bg-dark text-light">
        <div class="container-fluid">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">No Invoice</th>
                        <th scope="col">Nilai</th>
                        <th scope="col">Komentar</th>
                        <th scope="col">Tanggal Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($review as $r) : ?>
                        <tr>
                            <th scope="row"><?php echo $r['id'] ?></th>
                            <td><?php echo $r['noInvoice'] ?></td>
                            <td><?php echo $r['nilai'] ?></td>
                            <td><?php echo $r['komentar'] ?></td>
                            <td><?php echo $r['tglKomen'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Use absolute path relative to this file

$reports = query("SELECT * FROM report");
?>

<body class="bg-dark">
    <div class="content mt-3 bg-dark text-light">
        <div class="container-fluid">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report) : ?>
                        <tr>
                            <th scope="row"><?php echo $report['id'] ?></th>
                            <td><?php echo $report['jenis'] ?></td>
                            <td><?php echo $report['nama'] ?></td>
                            <td><?php echo $report['deskripsi'] ?></td>
                            <td><?php echo $report['kontak'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '../../main-layouts.php';
require_once __DIR__ . '/../../models/dbConfig.php'; // Gunakan path absolut relatif terhadap file ini

$user = query("SELECT * FROM users")
?>

<body class="bg-dark">
    <div class="content mt-3 bg-dark text-light">
        <div class="container-fluid">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user as $u) : ?>
                        <tr>
                            <th scope="row"><?php echo $u['id'] ?></th>
                            <td><?php echo $u['name'] ?></td>
                            <td><?php echo $u['username'] ?></td>
                            <td><?php echo $u['email'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
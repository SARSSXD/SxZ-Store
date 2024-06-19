<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="shortcut icon" href="../../assets/img/logo.jpg" type="image/x-icon">

    <title>SxZ Store</title>
    <style>
        .dataTables_filter {
            float: right;
        }

        .dataTables_length {
            float: left;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/views/user/dashUser.php"><img src="../../assets/img/logo2.png" alt="Logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (isset($_SESSION['admin'])) : ?>
                <ul class="navbar-nav">
                    <li class="nav-item <?php echo $current_page == 'dashAdmin.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/admin/dashAdmin.php">Beranda <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?php echo $current_page == 'item.php' ? 'active' : '';
                                        echo $current_page == 'tambahItem.php' ? 'active' : '';
                                        echo $current_page == 'editItem.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/admin/item.php">Item</a>
                    </li>
                    <?php if ($current_page === 'tambahNominal.php' || $current_page === 'editNominal.php') : ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Nominal</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item <?php echo $current_page == 'promo.php' ? 'active' : '';
                                        echo $current_page == 'tambahPromo.php' ? 'active' : '';
                                        echo $current_page == 'editPromo.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/admin/promo.php">Promo</a>
                    </li>
                    <li class="nav-item <?php echo $current_page == 'transaksi.php' ? 'active' : '';
                                        echo $current_page == 'lihatBukti.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/admin/transaksi.php">Transaksi<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?php echo $current_page == 'report.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/admin/report.php">Report<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?php echo $current_page == 'review.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/admin/review.php">Review<span class="sr-only">(current)</span></a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="/assets/img/user.jpg" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%;">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profile.php"><?php echo $_SESSION['user']['username']; ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </ul>
            <?php else : ?>
                <ul class="navbar-nav">
                    <li class="nav-item <?php echo $current_page == 'dashUser.php'  ? 'active' : '';
                                        echo $current_page == 'index.php'  ? 'active' : ''; ?>">
                        <a class="nav-link" href="dashUser.php">Beranda<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?php echo $current_page == 'cekTransaksi.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/user/cekTransaksi.php">Cek Transaksi</a>
                    </li>
                    <li class="nav-item <?php echo $current_page == 'leaderboard.php' ? 'active' : ''; ?>">
                        <a class="nav-link" href="/views/user/leaderboard.php">Leaderboard</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <ul class="navbar-nav ml-auto">
                        <?php if (isset($_SESSION['user'])) : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="/assets/img/user.jpg" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%;">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="profile.php"><?php echo $_SESSION['user']['username']; ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/views/user/report.php">Report</a>
                                    <a class="dropdown-item" href="/logout.php">Logout</a>
                                </div>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/views/user/report.php">Report</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/register.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </ul>
            <?php endif ?>
        </div>
    </nav>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.0.8/datatables.min.js"></script>
    <script src="/assets/js/sweetalert2@11.js"></script>
    <script src="/assets/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php if ($current_page != 'leaderboard.php') : ?>
                $('.table').DataTable();
            <?php endif; ?>
        });
    </script>

</body>

</html>
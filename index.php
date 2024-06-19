<?php
session_start();

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
    case '':
        require __DIR__ . '/views/user/dashUser.php';
        break;
    case '/admin':
        require __DIR__ . '/controllers/AdminDashboardController.php';
        $controller = new AdminDashboardController();
        $controller->index();
        break;
    case '/user':
        require __DIR__ . '/controllers/UserDashboardController.php';
        $controller = new UserDashboardController();
        $controller->index();
        break;
    case '/item':
        require __DIR__ . '/controllers/ItemController.php';
        $controller = new ItemController();
        $controller->show();
        break;
    case '/leaderboard':
        require __DIR__ . '/controllers/LeaderboardController.php';
        $controller = new LeaderboardController();
        $controller->show();
        break;
    case '/transaction':
        require __DIR__ . '/controllers/TransactionController.php';
        $controller = new TransactionController();
        $controller->check();
        break;
    default:
        http_response_code(404);
        echo "Page not found";
        break;
}
?>

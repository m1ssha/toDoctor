<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

require('../database/database.php');

$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id",['id' => $_SESSION['telegram_id']]);

if ($user[0]['is_admin'] != 1) {
    header('Location: ../user/profile.php');
    exit();
}

$firstName = $user[0]['first_name'];

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление</title>
    <link rel="icon" href="../src/image/toDoctor.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>

<body class="bg-dark text-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">
            <img src="../src/image/navbar-logo.png" alt="" width="133" height="35" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/profile.php">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../enroll/doctors.php">Записаться</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="jumbotron bg-dark text-light">
            <h1>Панель администратора</h1>
            <p class="lead">Добро пожаловать, <strong><?php echo $firstName; ?></strong>!</p>
            <p class="lead">
                <a class="btn btn-light btn-lg" href="users.php">Пользователи</a>
                <a class="btn btn-light btn-lg" href="doctors.php">Врачи</a>
                <a class="btn btn-light btn-lg" href="enrolls.php">Записи</a>
                <a class="btn btn-light btn-lg" href="numbers.php">Номера</a>
            </p>
        </div>
    </div>
    
</body>

</html>
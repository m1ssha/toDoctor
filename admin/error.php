<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database.php');

$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id",['id' => $_SESSION['telegram_id']]);

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($user[0]['is_admin'] != 1) {
    header('Location: ../user/profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="../src/image/toDoctor.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>

<body class="bg-dark text-light">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">
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
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="jumbotron bg-dark text-light">
            <h1 class="display-4"><strong>Ошибка</strong></h1>
            <p class="lead">Уважаемый администратор! При обработке вашего запроса произошла ошибка.
                <br>Пожалуйста, попробуйте заново.
            </p>
            <p>
                <a class="btn btn-outline-light" href="panel.php">Вернуться</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE) {
    die(header('Location: ../user/profile.php'));
}

require('../database/config.php');

define('BOT_USERNAME', $BOT_USERNAME);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="icon" href="../src/image/toDoctor.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body class="bg-dark text-light">
    <div class="container">
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
                        <a class="nav-link" href="user/profile.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="enroll/doctors.php">Записаться</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container bg-dark text-light">
        <div class="row justify-content-center bg-dark text-light">
            <div class="col-md-6 bg-dark text-light">
                    <div class="card-body mx-auto text-center bg-dark text-light">
                        <h2 class="card-title">Авторизация</h2>
                        <p>Для использования системы <strong>toDoctor</strong> требуется авторизоваться через <strong>Telegram</strong></p>
                        <script async src="https://telegram.org/js/telegram-widget.js?22"
                            data-telegram-login="<?= BOT_USERNAME ?>" data-size="large" data-userpic="true" data-radius="15"
                            data-auth-url="logic/auth.php">
                        </script>
                    </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            window.TelegramAuthCallback = function (user) {
                var data = {
                    first_name: user.first_name,
                    last_name: user.last_name,
                    telegram_id: user.id,
                    telegram_username: user.username,
                    profile_picture: user.photo_url,
                    auth_date: user.auth_date
                };

                $.ajax({
                    url: 'auth.php',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response === "success") {
                            window.location.href = '../user/profile.php';
                        } else {
                            $('#errorContainer').show().text(response);
                        }
                    },
                    error: function () {
                        $('#errorContainer').show().text('Ошибка выполнения запроса.');
                    }
                });
            };

            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                var login = $('#login').val();
                var password = $('#password').val();

                $.ajax({
                    url: 'auth.php',
                    type: 'POST',
                    data: {
                        login: login,
                        password: password
                    },
                    success: function (response) {
                        if (response === "success") {
                            window.location.href = '../user/profile.php';
                        } else {
                            $('#errorContainer').show().text(response);
                        }
                    },
                    error: function () {
                        $('#errorContainer').show().text('Ошибка выполнения запроса.');
                    }
                });
            });
        });
    </script>

    <footer class="bg-dark text-light mt-4 fixed-bottom">
        <div class="container py-3">
            <div class="row">
                <div class="col-12 text-center">
                    <p>Система <strong>toDoctor</strong> разработана в рамках курсовой работы по основам программирования<br>&copy; <?php echo date("Y"); ?> MN.</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
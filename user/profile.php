<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    die(header('Location: ../auth/login.php'));
}

require('../database/database.php');
$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id",['id' => $_SESSION['telegram_id']]);

$firstName = $user[0]['first_name'];
$telegramID = $user[0]['telegram_id'];
$telegramUsername = $user[0]['telegram_username'];
$userID = $user[0]['id'];
$isAdmin = ($user[0]['is_admin'] == 1);

$enrolls = $db->Select("SELECT * FROM enrolls WHERE user_id = :user_id", ['user_id' => $telegramID]);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body class="bg-dark text-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">toDoctor</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Главная</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="">Профиль <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../enroll/doctors.php">Записаться</a>
                </li>
                <?php if ($isAdmin) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 bg-dark text-light">
        <div class="jumbotron bg-dark text-light">
            <h1 class="display-5"><strong><?php echo $firstName ?></strong>, это ваш профиль</h1>
            <p>Здесь вы можете посмотреть вашу историю посещений врачей</p>
            <p>
                <a href="../enroll/doctors.php" class="btn btn-outline-light">Записаться к врачу</a>
            </p>
        </div>
    </div>

    <div class="container bg-dark text-light">
    <h2>История</h2>
    <?php if (!empty($enrolls)) : ?>
        <table class="table table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Врач</th>
                    <th scope="col">Специализация</th>
                    <th scope="col">Номер в очереди</th>
                    <th scope="col">Время записи</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrolls as $enroll) : ?>
                    <?php
                    $doctorInfo = $db->Select("SELECT * FROM `doctors` WHERE `name` = :doctor_name", ['doctor_name' => $enroll['doctor_name']]);
                    $specialization = (!empty($doctorInfo) ? $doctorInfo[0]['specialization'] : 'N/A');
                    ?>
                    <tr>
                        <td><?php echo $enroll['doctor_name']; ?></td>
                        <td><?php echo $specialization; ?></td>
                        <td><?php echo $enroll['number']; ?></td>
                        <td><?php echo $enroll['enroll_time']?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="lead"><strong>В базе данных не содержится данных о записи</strong></p>
    <?php endif; ?>
</div>

    <div class="container mt-2">
        <p>
            <a class="btn btn-outline-danger" href="../auth/logic/logout.php">Выйти из аккаунта</a>
        </p>
    </div>

</body>
</html>

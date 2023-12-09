<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

require('../database/database.php');

$user = $db->Select("SELECT * FROM `users` WHERE `telegram_id` = :id", ['id' => $_SESSION['telegram_id']]);

if ($user[0]['is_admin'] != 1) {
    header('Location: ../user/profile.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggleStatus'])) {
        $enrollId = $_POST['enrollId'];

        $db->Update("UPDATE `enrolls` SET `status` = IF(`status` = 'Принят', 'Не принят', 'Принят') WHERE `id` = :enrollId", ['enrollId' => $enrollId]);

        header('Location: enrolls.php');
        exit();
    }
}

$enrolls = $db->Select("SELECT * FROM `enrolls`");
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление</title>
    <link rel="icon" href="../src/image/toDoctor.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body class="bg-dark text-light d-flex flex-column h-100">
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
                        <a class="nav-link" href="../user/profile.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../enroll/doctors.php">Записаться</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="../admin/panel.php">Панель администрирования</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-3">
        <p>
            <a class="btn btn-outline-light" href="panel.php">Вернуться</a>
        </p>
    </div>

    <div class="container bg-dark text-light">
        <h2>Все записи</h2>
        <?php if (!empty($enrolls)) : ?>
            <table class="table table-dark table-striped table-hover table-borderless table-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Врач</th>
                        <th scope="col">Номер</th>
                        <th scope="col">Время изменения</th>
                        <th scope="col">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrolls as $enroll) : ?>
                        <tr>
                            <th scope="row"><?php echo $enroll['id']; ?></th>
                            <td><?php echo $enroll['user_id']; ?></td>
                            <td><?php echo $enroll['doctor_name']; ?></td>
                            <td><?php echo $enroll['number']; ?></td>
                            <td><?php echo $enroll['enroll_time']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="enrollId" value="<?php echo $enroll['id']; ?>">
                                    <?php
                                        $buttonClass = ($enroll['status'] == 'Принят') ? 'btn-outline-danger' : 'btn-outline-success';
                                    ?>
                                    <button type="submit" name="toggleStatus" class="btn <?php echo $buttonClass; ?>">
                                        <?php echo ($enroll['status'] == 'Принят') ? 'Не принят' : 'Принять'; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>У вас пока нет записей.</p>
        <?php endif; ?>
    </div>
</body>
</html>
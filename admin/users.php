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

$users = $db->Select("SELECT * FROM `users`");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggleAdmin'])) {
    $user_id = $_POST['user_id'];

    if ($user[0]['id'] != $user_id) {
        $is_admin = $db->Select("SELECT `is_admin` FROM `users` WHERE `id` = :id", ['id' => $user_id])[0]['is_admin'];

        $new_is_admin = ($is_admin == 1) ? 0 : 1;

        $db->Update("UPDATE `users` SET `is_admin` = :is_admin WHERE `id` = :id", ['is_admin' => $new_is_admin, 'id' => $user_id]);

        header('Location: users.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $user_id = $_POST['user_id'];

    if ($user[0]['id'] != $user_id) {
        $db->Update("DELETE FROM users WHERE id = :id ", ['id' => $user_id]);
        header('Location: users.php');
        exit();
    }
}
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

    <div class="container mt-3">
        <p><a href="panel.php">Вернуться</a></p>
    </div>

    <div class="container mt-1 bg-dark text-light">
        <h2>Все пользователи</h2>
        <?php if (!empty($users)) : ?>
            <table class="table table-dark table-striped table-hover table-borderless table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Telegram ID</th>
                        <th scope="col">Имя пользователя</th>
                        <th scope="col">Админ</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <th scope="row"><?php echo $user['id']; ?></th>
                            <th scope="row"><?php echo $user['telegram_id']; ?></th>
                            <td scope="row"><?php echo $user['first_name']; ?></td>
                            <td scope="row">
                                <form method="post" action="">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="toggleAdmin" class="btn btn-sm btn-<?php echo ($user['is_admin'] == 1) ? 'outline-danger' : 'outline-success'; ?>">
                                        <?php echo ($user['is_admin'] == 1) ? 'Снять администратора' : 'Назначить администратором'; ?>
                                    </button>
                                </form>
                            <td scope="row">
                                <form method="post" action="">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="deleteUser" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>В системе не зарегистрированы пользователи</p>
        <?php endif; ?>
    </div>

</body>

</html>
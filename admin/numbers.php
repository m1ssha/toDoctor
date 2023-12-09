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

$numbers = $db->Select("SELECT * FROM `numbers`");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_number']) && is_numeric($_POST['new_number'])) {
        $newNumber = $_POST['new_number'];

        $db->Insert("INSERT INTO `numbers` (`number`) VALUES (:number)", ['number' => $newNumber]);

        header('Location: numbers.php');
        exit();
    } elseif (isset($_POST['delete_number'])) {
        $numberId = $_POST['number_id'];
        $db->Insert("DELETE FROM `numbers` WHERE `id` = :id", ['id' => $numberId]);
        header('Location: numbers.php');
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
                        <a class="nav-link" href="../user/profile.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../enroll/doctors.php">Записаться</a>
                    </li>
                    <li class="nav-item active active">
                        <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
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
        <form action="" method="post">
            <div class="form-group">
                <label for="new_number">Добавить номер:</label>
                <input type="text" class="form-control" id="new_number" name="new_number" placeholder="Введите номер" required>
            </div>
            <button type="submit" class="btn btn-outline-light" name="add_number">Добавить</button>
        </form>
    </div>

    <div class="container bg-dark text-light mt-3">
        <h2>Все номера</h2>
        <?php if (!empty($numbers)) : ?>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Номер</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($numbers as $number) : ?>
                        <tr>
                            <th scope="row"><?php echo $number['id']; ?></th>
                            <td><?php echo $number['number']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="number_id" value="<?php echo $number['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger" name="delete_number">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>В базе данных не содержится информации о номерах</p>
        <?php endif; ?>
    </div>

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
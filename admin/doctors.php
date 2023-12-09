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

$doctors = $db->Select("SELECT * FROM `doctors`");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];

    $db->Insert("INSERT INTO `doctors` (`name`, `specialization`) VALUES (:name, :specialization)", [
        'name' => $name,
        'specialization' => $specialization
    ]);

    header('Location: doctors.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_doctor'])) {
    $doctorId = $_POST['doctor_id'];

    $db->Insert("DELETE FROM `doctors` WHERE `id` = :id", ['id' => $doctorId]);

    header('Location: doctors.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_doctor'])) {
    $doctorId = $_POST['doctor_id'];
    $editName = $_POST['edit_name'];
    $editSpecialization = $_POST['edit_specialization'];

    $db->Update("UPDATE `doctors` SET `name` = :name, `specialization` = :specialization WHERE `id` = :id", [
        'name' => $editName,
        'specialization' => $editSpecialization,
        'id' => $doctorId
    ]);

    header('Location: doctors.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .modal-header .close {
            color: white;
        }
    </style>

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

    <div class="container bg-dark text-light">
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Имя врача:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="specialization">Специализация:</label>
                <input type="text" class="form-control" id="specialization" name="specialization" required>
            </div>
            <button type="submit" class="btn btn-outline-light" name="add_doctor">Добавить врача</button>
        </form>
    </div>

    <div class="container bg-dark text-light mt-3">
        <h2>Врачи</h2>
        <?php if (!empty($doctors)) : ?>
            <table class="table bg-dark text-light">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Врач</th>
                        <th scope="col">Специализация</th>
                        <th scope="col">Редактировать</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor) : ?>
                        <tr>
                            <th scope="row"><?php echo $doctor['id']; ?></th>
                            <td><?php echo $doctor['name']; ?></td>
                            <td><?php echo $doctor['specialization']; ?></td>
                            <td>
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#editModal<?php echo $doctor['id']; ?>">Редактировать</button>
                            </td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger" name="delete_doctor">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal<?php echo $doctor['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $doctor['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog bg-dark text-light" role="document">
                                <div class="modal-content bg-dark text-light">
                                    <div class="modal-header bg-dark text-light">
                                        <h5 class="modal-title bg-dark text-light" id="editModalLabel<?php echo $doctor['id']; ?>">Редактировать врача</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-dark text-light">
                                        <form method="post" action="">
                                            <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                                            <div class="form-group">
                                                <label for="edit_name">Имя врача:</label>
                                                <input type="text" class="form-control" id="edit_name" name="edit_name" value="<?php echo $doctor['name']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_specialization">Специализация:</label>
                                                <input type="text" class="form-control" id="edit_specialization" name="edit_specialization" value="<?php echo $doctor['specialization']; ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="edit_doctor">Сохранить изменения</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>В базе данных не содержится информации о врачах</p>
        <?php endif; ?>
    </div>
</body>

</html>
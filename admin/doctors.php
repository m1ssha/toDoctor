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

$doctors = $db->Select("SELECT * FROM `doctors`");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $description = $_POST['description'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_formats = ['image/bmp', 'image/jpeg', 'image/png'];
        $file_format = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($file_format, $allowed_formats)) {
            header("Location: error.php");
            exit();
        }

        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $image = null;
    }

    $db->Insert("INSERT INTO `doctors` (`name`, `specialization`, `image`, `description`) VALUES (:name, :specialization, :image, :description)", [
        'name' => $name,
        'specialization' => $specialization,
        'image' => $image,
        'description' => $description
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
    $editDescription = $_POST['edit_description'];

    $db->Update("UPDATE `doctors` SET `name` = :name, `specialization` = :specialization, `description` = :description WHERE `id` = :id", [
        'name' => $editName,
        'specialization' => $editSpecialization,
        'description' => $editDescription,
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
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Имя врача:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="specialization">Специализация:</label>
                <input type="text" class="form-control" id="specialization" name="specialization" required>
            </div>
            <div class="form-group">
                <label for="description">О себе:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Фотография:</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-outline-light mt-2" name="add_doctor">Добавить врача</button>
        </form>
    </div>

    <div class="container bg-dark text-light mt-3">
        <h2>Врачи</h2>
        <?php if (!empty($doctors)) : ?>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th scope="col">Врач</th>
                        <th scope="col">Специализация</th>
                        <th scope="col">Редактировать</th>
                        <th scope="col">О себе</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor) : ?>
                        <tr>
                            <td><?php echo $doctor['name']; ?></td>
                            <td><?php echo $doctor['specialization']; ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#editModal<?php echo $doctor['id']; ?>">Редактировать</button>
                            </td>
                            <td><?php echo $doctor['description']; ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger" name="delete_doctor">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal<?php echo $doctor['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $doctor['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog bg-dark text-ligh modal-lg" role="document">
                                <div class="modal-content bg-dark text-light">
                                    <div class="modal-header bg-dark text-light">
                                        <h5 class="modal-title bg-dark text-light" id="editModalLabel<?php echo $doctor['id']; ?>">Редактировать врача</h5>
                                        <button type="button" class="btn btn-outline-warning close mt-2" data-dismiss="modal" aria-label="Close">Закрыть</button>
                                    </div>
                                    <div class="modal-body bg-dark text-light">
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                                            <div class="form-group">
                                                <label for="edit_name">Имя врача:</label>
                                                <input type="text" class="form-control" id="edit_name" name="edit_name" value="<?php echo $doctor['name']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_specialization">Специализация:</label>
                                                <input type="text" class="form-control" id="edit_specialization" name="edit_specialization" value="<?php echo $doctor['specialization']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_description">Описание:</label>
                                                <textarea class="form-control" id="edit_description" name="edit_description" rows="7" required><?php echo $doctor['description']; ?></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-outline-primary mt-2" name="edit_doctor">Сохранить изменения</button>
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
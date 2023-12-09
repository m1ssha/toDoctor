<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database.php');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['doctor_id'])) {
    header('Location: ../error.php');
    exit();
}

$selectedDoctorId = $_GET['doctor_id'];

$doctorInfo = $db->Select("SELECT id, name, specialization FROM doctors WHERE id = :id", [':id' => $selectedDoctorId]);
if (empty($doctorInfo)) {
    header('Location: ../error.php');
    exit();
}

$selectedDoctorName = $doctorInfo[0]['name'];
$selectedDoctorSpecialization = $doctorInfo[0]['specialization'];
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успешная запись к врачу <?php echo $selectedDoctorName ?></title>
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
                        <a class="nav-link" href="../index.php">Главная</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/profile.php">Профиль</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="">Записаться</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <h2>Уважаемый пользователь!</h2>
        <p class="lead">Вы успешно записаны к врачу <strong><?php echo $selectedDoctorName; ?></strong> (<strong><?php echo $selectedDoctorSpecialization; ?></strong>).</p>
        <p class="lead">Подробнее о записи вы можете узнать в своём <a href="../user/profile.php"><strong>профиле</strong></a>.</p>
        <a href="../index.php" class="btn btn-outline-light">На главную</a>
    </div>
</body>
</html>
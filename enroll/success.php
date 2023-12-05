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
    <title>Успешная запись к врачу</title>
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
                    <a class="nav-link" href="../index.php">Главная</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/profile.php">Профиль</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="doctors.php">Записаться</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Уважаемый пользователь!</h2>
        <p class="lead">Вы успешно записаны к врачу <strong><?php echo $selectedDoctorName; ?></strong> (<strong><?php echo $selectedDoctorSpecialization; ?></strong>).</p>
        <p class="lead">Подробнее о записи вы можете узнать в своём <a href="../user/profile.php">профиле</a></p>
        <a href="../index.php" class="btn btn-primary">На главную</a>
    </div>
</body>

</html>
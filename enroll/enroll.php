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

$doctorInfo = $db->Select("SELECT * FROM doctors WHERE id = :id", [':id' => $selectedDoctorId]);
if (empty($doctorInfo)) {
    header('Location: ../error.php');
    exit();
}

$selectedDoctorName = $doctorInfo[0]['name'];
$selectedDoctorSpecialization = $doctorInfo[0]['specialization'];

$enrollNumber = $db->Select("SELECT number FROM enrolls WHERE doctor_id = :id", [':id' => $selectedDoctorId]);
$enrollNumbers = array_map(function ($seat) {
    return $seat['number'];
}, $enrollNumber);

$seatsData = $db->Select("SELECT id, number FROM numbers");
$allSeatNumbers = array_map(function ($seat) {
    return $seat['number'];
}, $seatsData);

$availableSeatNumbers = array_diff($allSeatNumbers, $enrollNumbers);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['telegram_id'];
    $selectedNumberID = $_POST['number'];

    $insertData = [
        'user_id' => $userID,
        'doctor_id' => $selectedDoctorId,
        'doctor_name' => $selectedDoctorName,
        'number' => $selectedNumberID,
    ];

    $db->Insert("INSERT INTO enrolls (" . implode(', ', array_keys($insertData)) . ") VALUES (:" . implode(', :', array_keys($insertData)) . ")", $insertData);

    header("Location: success.php?doctor_id=$selectedDoctorId");
    exit();
}

$numbersData = $db->Select("SELECT * FROM numbers");
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записаться</title>
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

    <div class="container mt-2">
        <a href="doctors.php">Вернуться</a>
        <?php if (!empty($selectedDoctorName)): ?>
            <?php if (empty($availableSeatNumbers)): ?>
                <div class="container mt-2">
                    <h2>Уважаемый пользователь!</h2>
                    <p>Все места к врачу <strong><?php echo $selectedDoctorName; ?></strong> забронированы, приносим свои извинения</p>
                </div>
            <?php else: ?>
                <div class="mt-2">
                    <h2><?php echo $selectedDoctorName; ?></h2>
                    <div style="width: 200px; height: 200px; overflow: hidden; border-radius: 50%;">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($doctorInfo[0]['image']); ?>" class="img-fluid" alt="<?php echo $selectedDoctorName; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <p class="lead mt-2"><strong>Специализация:</strong> <?php echo $doctorInfo[0]['specialization'] ?></p>
                    <p class="lead mt-2"><strong>Описание:</strong> <?php echo $doctorInfo[0]['description']?></p>
                </div>
                <h2>Выберите свободное место для записи</h2>
                <form id="enrollForm" method="post" action="">
                    <div class="form-group">
                        <select class="form-control form-control-lg" id="numberSelect" name="number" required>
                            <?php foreach ($numbersData as $number): ?>
                                <option value="<?php echo $number['number']; ?>" <?php echo in_array($number['number'], $availableSeatNumbers) ? '' : 'disabled'; ?>>
                                    <?php echo $number['number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-light mt-2">Записаться к врачу</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <h2>Выберите место</h2>
        <?php endif; ?>
    </div>
</body>
</html>
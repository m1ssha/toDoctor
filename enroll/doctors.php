<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database.php');

$data = $db->Select("SELECT id, name, specialization FROM doctors");
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

    <div class="container mt-2 bg-dark text-light">
        <h2>Запись к врачу</h2>
        <table class="table table-dark table-borderless table-hover">
            <thead>
                <tr>
                    <th scope="col">Врач</th>
                    <th scope="col">Специализация</th>
                    <th scope="col">Записаться</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $doctor) : ?>
                        <tr>
                            <th scope="row"><?php echo $doctor['name']; ?></th>
                            <th scope="row"><?php echo $doctor['specialization']; ?></th>
                            <td>
                                <form action="enroll.php" method="get">
                                    <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                                    <button type="submit" class="btn btn-outline-light">Записаться</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
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
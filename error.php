<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="src/image/toDoctor.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>

<body class="bg-dark text-light">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">
                <img src="src/image/navbar-logo.png" alt="" width="133" height="35" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user/profile.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="enroll/doctors.php">Записаться</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="jumbotron bg-dark text-light">
            <h1 class="display-4"><strong>Ошибка</strong></h1>
            <p class="lead">Уважаемый пользователь! При обработке вашего запроса произошла ошибка.
                <br>Пожалуйста, попробуйте заново.
            </p>
        </div>
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

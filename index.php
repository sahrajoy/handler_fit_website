<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- css -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/footer_style.css">
    <link rel="stylesheet" href="css/navbar_styles.css">
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Email Icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Instagram Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
    <!-- navbar -->
    <?php
    $loc = "";
    require_once "components/navbar.php"; ?>
    
    <!-- hero section -->
    <div id="hero">
        <div class="text-center" id="overlay">
            <h2>"Dein Weg zu einem fitteren Leben <br>
            – mit einem persönlichen Fitness Trainer der Dich <br>
            motiviert und unterstützt!"</h2>
            <div class="btns">
                <div class="btn btn-primary" id="online">Online coaching</div>
                <div class="btn btn-primary" id="personal">Personal Training</div>
            </div>
        </div>
    </div>

    <!-- about section -->
    <div class="text-center" id="about">
        <h1 class="offset-2">Welcome,</h1>
        <h3 class="offset-2">in my world of passionate training!</h3>
        <p class="offset-2" >"Welcome to my fascinating world of passionate training! <br>
        As an experienced fitness trainer, I accompany you on your  <br>
        fitness journey, including tailor-made training methods, <br>
        professional instructions and unlimited motivation. <br>
        Discover your body's full potential and achieve <br>
        your individual goals. Are you ready to <br>
        experience the change? Let's get started together!"</p>
    </div>

    <!-- offert section -->
    <div class="row m-0" id="offert">
        <div class="col-md-5 m-4" id="onlinecoaching">
            <a href="onlinecoaching.php">
                <div class="imgOnline">
                    <div class="text-center" id="overlayOnline">
                        <h2>Online Coaching</h2>
                    </div>
                    <div id="overlayEffectOnline">
                        <h2>Online Coaching</h2>
                    </div>
                </div>
            </a>
            <div class="d-flex flex-column align-items-center justify-content-center pt-5">
                <a href="online_coaching/online_coaching.php"><p class="p-2">Weight-loss coaching</p></a>
                <a href="online_coaching/fitness_nutrition_tips.php"><p class="p-2">Fitness & Nutrition Tips</p></a>
                <a href="online_coaching/training_plans.php"><p class="p-2">Trainingplans</p></a>
            </div>
        </div>
        <div class="col-md-5 m-4" id="personaltraining">
            <a href="personaltraining.php">
                <div class="imgPersonal">
                    <div class="text-center" id="overlayPersonal">
                        <h2>Personal Training</h2>
                    </div>
                    <div id="overlayEffectPersonal">
                        <h2 class="headerHover">Personal Training</h2>
                    </div>
                </div>
            </a>
            <div class="d-flex flex-column align-items-center justify-content-center pt-5">
                <a href="personal_training/hypertrophy.php"><p class="p-2">Hypertrophy (Muscle Building)</p></a>
                <a href="personal_training/strength_training.php"><p class="p-2">Strength Training</p></a>
                <a href="personal_training/belly-butt-thigh.php"><p class="p-2">Belly-Butt-Thigh Workout</p></a>
            </div>
        </div>
        <a class="btn btn-primary py-2 mb-5" id="buttonCons">get your free initial consultation</a>
    </div>

    <!-- footer -->
    <?php
    $loc = "";
    require_once "components/footer.php"; ?>

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- icons -->
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</body>
</html>
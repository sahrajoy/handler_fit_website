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
    <link rel="stylesheet" href="css/footer_styles.css">
    <link rel="stylesheet" href="css/navbar_styles.css">
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
    
    <div class="container">
        <h1>Welcome,<br>in my world of passionate training!</h1>
        <p>"Welcome to my fascinating world of passionate training! As an experienced fitness trainer, I accompany you on your fitness journey, including tailor-made training methods, professional instructions and unlimited motivation. Discover your body's full potential and achieve your individual goals. Are you ready to experience the change? Let's get started together!"</p>
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
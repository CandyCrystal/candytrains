<?php
include '../config/connect.php';
$platformName = substr($_GET["platformName"], 0, 2);

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Platform departures - CandyTrains</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/platform.css">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body class="display">
    <div class="platformNum">
        <p class="platformNumLine1">Spor</p>
        <p class="platformNumLine2">Track</p>
        <p class="platformNumLine3"><?php echo $platformName ?></p>
    </div>
</body>

</html>
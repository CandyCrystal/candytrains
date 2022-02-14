<?php
include "../config/connect.php";

$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$hideCopyright = $_GET["hideCopyright"];

$train = $_GET["train"];
if ($train == "") {
    $train = 2281;
}

$displayType = $_GET["type"];
if ($displayType == "") {
    $displayType = "flirt";
}
$color = "#629aa4";

switch ($displayType) {
    case "flirt":
        $iframe = './flirtMonitor.php?train=' . $train;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "On train monitor for train " . $train;
        $extendedTitle = $title;
        $color = "194a9f";
        break;
    case "future":
        $iframe = './futureTrainMonitor.php?train=' . $train;
        $ratio[0] = 20;
        $ratio[1] = 3;
        $title = "On train monitor for train " . $train;
        $extendedTitle = $title;
        $color = "194a9f";
        break;
}
$paddingTop = $ratio[1] / $ratio[0] * 100;
$aspectRatio = $ratio[0] . "/" . $ratio[1];
$viewerSize = "height: 100vmin; width: " . $ratio[0] / $ratio[1] * 100 . "vmin; ";
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/monitor.css">
    <title><?php echo $title ?> - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title ?> - Candytrains" />
    <meta property="og:description" content="<?php echo $extendedTitle ?> on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="<?php echo $thisLink ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="<?php echo $color ?>">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />


    <style>
        .wrap2 {
            padding-top: <?php echo $paddingTop . "%" ?>;
        }

        @media screen and (min-aspect-ratio: <?php echo $aspectRatio ?>) {
            .viewer {
                /* border: none; */
                position: relative;
                display: block;
                margin: 0 auto;
                <?php echo $viewerSize ?>
            }

            .wrap2 {
                height: unset;
                padding-top: unset !important;
                position: unset;
                width: 100vw;
            }
        }
    </style>
</head>

<body>
    <?php
    if ($hideCopyright != true && $displayType != "flirt") { ?>
        <!-- <div class="copyright">Inneholder data under Norsk lisens for offentlige data (NLOD) tilgjengeliggjort av Bane NOR<?php echo $extra ?></div> -->
    <?php } ?>
    <div class="wrap">
        <div class="wrap2">
            <iframe class="viewer" src="<?php echo $iframe ?>">
        </div>
    </div>
</body>

</html>
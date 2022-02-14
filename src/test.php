<?php
$lines = ["FLY1", "F1x", "FLY2", "F2x", "L1", "L1x", "R10", "R10x", "R11", "R11x", "L12", "L12x", "L13", "L13x", "L14", "L14x", "R15", "R15x", "L2", "L2x", "R20", "R20x", "L21", "L21x", "L22", "L22x", "L3", "L3x", "R30", "R30x", "L4", "L4x", "R40", "R40x", "R41", "R41x", "L42", "L42x", "L5", "L5x", "R50", "R50x", "L51", "L51x", "L52", "L52x", "L6", "L6x", "R60", "R60x", "R61", "R61x", "L62", "L62x", "L7", "L7x", "R70", "R70x", "L71", "L71x", "L8", "L8x", "R80", "R80x", "VY", "VYT", "VYG", "FLY", "SJ", "SJN", "GAG", "GC", "GR", "RCT", "CN", "BLS", "TM", "HER", "TÅB", "ONR", "MTA"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="./assets/css/lineImages.css" rel="stylesheet">
    <title>Document</title>
    <style>
        body {
            margin: 0;
            background-color: #77293e;
        }
    </style>
</head>

<body>
    <?php for ($i = 0; $i < count($lines); $i++) {
        echo '<div class="linebox medium2 ' . $lines[$i] . '"><div class="text">' . substr($lines[$i], 0, 1) . ' ' . substr($lines[$i], 1) . '</div></div>';
        if ($lines[$i] == "F2x" || $lines[$i] == "R15x" || $lines[$i] == "L22x" || $lines[$i] == "R30x" || $lines[$i] == "L42x" || $lines[$i] == "L52x" || $lines[$i] == "L62x" || $lines[$i] == "L71x" || $lines[$i] == "R80x") {
            echo "<br/><br/><br/><br/><br/>";
        }
        // if ($lines[$i] == "FLY2" || $lines[$i] == "R15" || $lines[$i] == "L22" || $lines[$i] == "R30" || $lines[$i] == "L42" || $lines[$i] == "L52" || $lines[$i] == "L62" || $lines[$i] == "L72") {
        //     echo "<br/><br/><br/><br/><br/>";
        // }
        // $i++;
    } ?>
</body>

</html>
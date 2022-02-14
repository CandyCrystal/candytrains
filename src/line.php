<?php
$line = $_GET["line"];
$class = $line;
if (strpos($line, "L") === false && strpos($line, "R") === false && strpos($line, "F") === false) {
    $class = "region";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Document</title>
</head>

<body>
    <table>
        <tr>
            <th>Search stations</th>
        </tr>
        <tr>
            <form autocomplete="off" action="./stationList.php" method="get">
                <th>
                    <input type="text" name="query">
                    <input type="submit" value="Search" class="button">
                </th>
            </form>
        </tr>
    </table>
    <table id="lineTable" class="<?php echo $class ?>">
    </table>
</body>

</html>

<script src="./lines.js"></script>
<script>
    var line = line_<?php echo $line ?>;
    var string = '<tr><th class="<?php echo $class ?>"><?php echo $line ?></th><th class="<?php echo $class ?>">' + line.description + '</th></tr>';
    for (i = 0; i < line.stations.length; i++) {
        var station = line.stations[i]
        console.log(station)
        string += "<tr><th><a href='./station.php?stationRef=" + station.code + "'>" + station.code + "</a></th><td><a href='./station.php?stationRef=" + station.code + "'>" + station.name + "</td></tr>"
    }
    string += "</table>"
    document.getElementById("lineTable").innerHTML = string
    console.log(line)
    console.log(string)
</script>
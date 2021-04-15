<?php $pageRequiresLogin = false;
include "./config/session.php";
include "./config/connect.php";
include "./config/candyDirectory.php";
include "./database/getStationData.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};

$stationQuery = new getStationData($databaseConnection);
$stationList = $stationQuery->getStations();


?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List of stations</title>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
</head>

<body>

    <table>
        <tr>
            <th></th>
            <th>Code</th>
            <th>Name</th>
        </tr>
        <?php while ($row = mysqli_fetch_array($stationList)) { ?>
            <tr>
                <th><a href="./station.php?stationRef=<?php echo $row["station_ref"]; ?>">View station</a></th>
                <td><?php echo $row["station_ref"]; ?></td>
                <td><?php echo $row["station_name"]; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <th colspan="3"><a href="./add/addStation.php">+ Add station</a></th>
        </tr>
    </table>
</body>

</html>

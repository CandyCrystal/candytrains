<?php
$pageRequiresLogin = true;
include "../config/session.php";
include "../config/connectNew.php";
include "../config/candyDirectory.php";
// include "../databaseQueries.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};

include "../database/stations.php";
$query = new stationQuery($databaseConnection);
$result = $query->getStations();

?>
<table>
    <form action="../database/stations.php?action=insert" method="post">
        <tr>
            <th>Ref | Closed?</th>
            <th>Date opened</th>
            <th>Latitude</th>
        </tr>
        <tr>
            <td>
                <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/dataLists/stationList.php">
                <input type="text" size="3" name="stationRef">
                <input type="checkbox" name="stationIsClosed" checked="true">
                <input type="submit" value="Add" class="button">
            </td>
            <td>
                <input type="date" name="stationOpenDate" value="0001-01-01">
            </td>
            <td>
                <input type="text" name="stationLat">
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <th>Name</th>
            <th>Date closed</th>
            <th>Longitude</th>
        </tr>
        <tr>
            <td>
                <input type="text" name="stationName">
            </td>
            <td>
                <input type="date" name="stationCloseDate" value="0001-01-01">
            </td>
            <td>
                <input type="text" name="stationLong">
            </td>

            <td>
            </td>
        </tr>
    </form>
</table>
<table>
    <tr>
        <th>REF</th>
        <th>Name</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Open date</th>
        <th>Close date</th>
        <th colspan="2">Closed?</th>
        <th colspan="2">Delete?</th>
    </tr>
    <!-- <?php while ($row = mysqli_fetch_array($result)) {
        $stationRef = $row["stationRef"];
        $isClosed = $row["stationIsClosed"];
        if ($isClosed == 1) {
            $isClosed = "checked='true'";
        }
    ?>
        <tr>
            <form action="../database/stations.php?action=update" method="post">
                <td>
                    <input hidden type="text" name="stationID" value="<?php echo $row["stationID"] ?>">
                    <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/dataLists/stationList.php">
                    <?php echo $stationRef ?>
                </td>
                <td>
                    <input type="text" name="stationName" value="<?php echo $row["stationName"] ?>">
                </td>
                <td>
                    <input type="text" name="stationLat" value="<?php echo $row["stationLat"] ?>">
                </td>
                <td>
                    <input type="text" name="stationLong" value="<?php echo $row["stationLong"] ?>">
                </td>
                <td>
                    <input type="date" name="stationOpenDate" value="<?php echo $row["stationOpenDate"] ?>">
                </td>
                <td>
                    <input type="date" name="stationCloseDate" value="<?php echo $row["stationCloseDate"] ?>">
                </td>
                <td>
                    <input type="checkbox" name="stationIsClosed" <?php echo $isClosed ?>>
                </td>
                <td>
                    <input type="submit" value="Submit" class="button">
                </td>
            </form>
            <form action="../database/stations.php?action=delete" method="post">
                <td>
                    <input type="checkbox" required>
                </td>
                <td>
                    <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                    <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/dataLists/stationList.php">
                    <input type="submit" value="Delete" class="button">
                </td>
            </form>
        </tr>
    <?php  } ?> -->
</table>
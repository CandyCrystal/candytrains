<?php
$pageRequiresLogin = true;
include "../config/session.php";
include "../config/connectNew.php";
include "../config/candyDirectory.php";
include "../databaseQueries.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};

include "../database/lines.php";
$query = new lineQuery($databaseConnection);
$result = $query->getLines();

?>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    <?php while ($row = mysqli_fetch_array($result)) {
        $lineID = $row["lineID"] ?>
        <tr>
            <form action="../database/lines.php?action=update" method="post">
                <td>
                    <input hidden type="text" name="lineID" value="<?php echo $lineID ?>">
                    <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/dataLists/lineList.php">
                    <?php echo $lineID ?>
                </td>
                <td>
                    <input type="text" name="lineName" value="<?php echo $row["lineName"] ?>">
                </td>
                <td>
                    <input type="submit" value="Submit" class="button">
                </td>
            </form>
            <form action="../database/lines.php?action=delete" method="post">
                <td>
                    <input type="checkbox" required>
                    <input hidden type="text" name="lineID" value="<?php echo $lineID ?>">
                    <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/dataLists/lineList.php">
                    <input type="submit" value="Delete" class="button">
                </td>
            </form>
        </tr>
    <?php } ?>
    <tr>
        <form action="../database/lines.php?action=insert" method="post">
            <td>
                <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/dataLists/lineList.php">
            </td>
            <td>
                <input type="text" name="lineName" value="">
            </td>
            <td>
                <input type="submit" value="Add" class="button">
            </td>
        </form>
    </tr>
</table>
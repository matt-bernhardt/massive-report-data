<?php
/**
 * This loads all recorded player contracts based on a supplied 'player' parameter.
 *
 * @category None
 * @package  MassiveReportData
 * @author   Matt Bernhardt <matt.j.bernhardt@gmail.com>
 * @link     https://github.com/matt-bernhardt/massive-report-data
 */

if (true === isset($_GET['player'])) {
    $intPlayerID = $_GET['player'];
} else {
    $intPlayerID = 2938;
}

require_once "../includes/block_conn_open.php";

$sql  = "SELECT c.TeamID, t.Teamname, c.RosterNumber, TimeStamp(c.SigningDate) AS SortDate, date_format(c.SigningDate,'%c/%e/%y') AS SigningDate , s.ContractName, c.ContractLength, c.TransferFee, c.Notes, c.RelatedID, c.DesignatedPlayer ";
$sql .= "FROM tbl_contracts c ";
$sql .= "LEFT OUTER JOIN tbl_teams t ON c.TeamID = t.ID ";
$sql .= "LEFT OUTER JOIN tbl_teams pt ON c.LastTeamID = pt.ID ";
$sql .= "LEFT OUTER JOIN lkp_contractstatus s ON c.ContractType = s.ID ";
$sql .= "WHERE playerID = ".$intPlayerID." ";
$sql .= "ORDER BY SortDate ASC";

$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));

// Fetch the first result.
$res = $rs->fetch_array(MYSQLI_ASSOC);
?>
<table>
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Team</th>
            <th scope="col">Notes</th>
        </tr>
    </thead>
    <tbody>
<?php
// Do this as long as there is a valid result.
while (null !== $res) {
    echo '<tr>';
    echo '<td>'.$res['SigningDate'].'</td>';
    echo '<td>'.$res['Teamname'].'</td>';
    echo '<td>'.$res['Notes'].'</td>';
    echo '</tr>';
    // Fetch the next result.
    $res = $rs->fetch_array(MYSQLI_ASSOC);
}
?>
    </tbody>
</table>
<?php
require_once "../includes/block_conn_close.php";

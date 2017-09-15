
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once 'PHP/database.php';
date_default_timezone_set("Europe/Helsinki");
$db = new Database();


if (!isset($_SESSION['timeout'])) {
    die();
} else {
    $deviceId = $_GET["ID"];
    $timeRange = $_GET["time"];
    try{
        $order = $_GET["order"];
        $orderBy = $_GET["orderBy"];
    } catch (Exception $ex) {
        $order = "NULL";
        $orderBy = "NULL";
    }
    $activityData = $db->GetDeviceActivity($deviceId, $timeRange); // Activity data
    
    $rows = array();
    $cols = array(
        array("type" => "number"),
        array("type" => "number")
    );
    foreach ($activityData as $data) {
        $data["Time"] = new DateTime($data["Time"]);
        array_push($rows, array("c" => array(array("v" => $data["Time"]->format("H"), "f" => $data["Time"]->format("g A")), array("v" => $data["Occurences"], "f" => "Events: " . $data["Occurences"]))));
    }
    $table = array();

    $table["cols"] = $cols;
    $table["rows"] = $rows;

    $jsonTable = json_encode($table);
    ?>
    <div class="taulukkoDiv">
        <div class ="well nospace editedWell">
            <h3 class="page-header">Device Details</h3>
            <div class="well nospace table-responsive">
                <table class="table table-hover tableMarginZero">
                    <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Battery Level</th>
                            <th>Register Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $deviceData = $db->GetDeviceDetails($deviceId);
                        foreach ($deviceData as $variable) {
                            echo '
                    <tr>
                    <td>' . $variable["idDevice"] . '</td>
                    <td>' . htmlspecialchars($variable["Name"]) . '</td>
                    <td>' . htmlspecialchars($variable["Description"]) . '</td>   
                    <td>' . $variable["BatteryLevel"] . '</td>
                    <td>' . $variable["RegisterDate"] . '</td>
                    <td>';
                            if ($_SESSION["user"]["permission"] == 1) {
                                echo'
                    <span class="glyphicon glyphicon-edit text-success actionNappi" title="Change Description" onclick="changeDescription(' . $variable["idDevice"] . ')"></span>
                    ';
                            }
                            echo '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div> 
            <a href="index.php?site=Listed%20Devices" class="btn btn-default btn-lg" style="margin-top:10px;" role="button">Back</a>
        </div>

        <div class ="well nospace editedWell table-responsive " style="margin-top: 20px; padding-bottom: 30px;">
            <h3 class="page-header">Log data</h3>
            <div class="well nospace table-responsive" style="max-height: 500px;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Log Date
                                <span class="glyphicon glyphicon-triangle-top actionNappi" title="Order by Date ascending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=LogDate&orderBy=asc'"></span>
                                <span class="glyphicon glyphicon-triangle-bottom actionNappi" title="Order by Date descending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=LogDate&orderBy=desc'"></span>
                            </th>
                            <th>Level
                                <span class="glyphicon glyphicon-triangle-top actionNappi" title="Order by Level ascending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=level&orderBy=asc'"></span>
                                <span class="glyphicon glyphicon-triangle-bottom actionNappi" title="Order by Level descending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=level&orderBy=desc'"></span>
                            </th>
                            <th>Gesture Datis
                                <span class="glyphicon glyphicon-triangle-top actionNappi" title="Order by Gesture ascending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=gesture&orderBy=asc'"></span>
                                <span class="glyphicon glyphicon-triangle-bottom actionNappi" title="Order by Gesture descending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=gesture&orderBy=desc'"></span>
                            </th>
                            <th>Proximity Datis
                                <span class="glyphicon glyphicon-triangle-top actionNappi" title="Order by Proximity ascending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=proximity&orderBy=asc'"></span>
                                <span class="glyphicon glyphicon-triangle-bottom actionNappi" title="Order by Proxmimity descending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=proximity&orderBy=desc'"></span>
                            </th>
                            <th>Ambient Datis
                                <span class="glyphicon glyphicon-triangle-top actionNappi" title="Order by Ambient ascending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=ambient&orderBy=asc'"></span>
                                <span class="glyphicon glyphicon-triangle-bottom actionNappi" title="Order by Ambient descending" onClick="location.href='index.php?site=details&ID=<?php echo $deviceId?>&time=<?php echo $timeRange?>&order=ambient&orderBy=desc'"></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $logData = $db->GetDeviceLogs($deviceId,$order,$orderBy);
                        foreach ($logData as $variable) {
                            echo '
                    <tr>
                    <td>' . $variable["LogDate"] . '</td>
                    <td>' . $variable["Level"] . '</td>
                    <td>' . $variable["GestureData"] . '</td>   
                    <td>' . $variable["ProximityData"] . '</td>
                    <td>' . $variable["AmbientData"] . '</td>
                    <td>';
                            echo '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class ="well nospace editedWell table-responsive" style="margin-top: 20px; padding-bottom: 30px;">
            <h3 class="page-header">Device statistics</h3>
            <div class="dropdown dropMenu" style="margin-top: 30px; margin-bottom: 10px;">
                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">Change timeframe
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="http://projekti.yolo.fi/index.php?site=details&ID=<?php echo $deviceId ?>&time=day">1 Day</a></li>
                    <li><a href="http://projekti.yolo.fi/index.php?site=details&ID=<?php echo $deviceId ?>&time=week">A week</a></li>
                    <li><a href="http://projekti.yolo.fi/index.php?site=details&ID=<?php echo $deviceId ?>&time=month">A month</a></li>
                </ul>
            </div>
            <div id ="chart_div">
            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages': ['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(<?php echo $jsonTable; ?>);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        var options = {
<?php
switch ($timeRange) {

    case "day":
        echo 'title: "Time of Events (1 day)",';
        break;
    case "week":
        echo 'title: "Time of Events (1 week)",';
        break;
    case "month":
        echo 'title: "Time of Events (1 month)",';
        break;
}
?>
            width: 500,
            height: 500,
            legend: {position: "none"},
            chartArea: {width: "75%", height: "80%"},
            vAxis: {ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30], minValue: 1, title: "Number of Events"},
            hAxis: {ticks: [{v: 0, f: "12am"}, {v: 1, f: '1am'}, {v: 2, f: '2am'}, {v: 3, f: '3am'}, {v: 4, f: '4am'}, {v: 5, f: '5am'}, {v: 6, f: '6am'}, {v: 7, f: '7am'}, {v: 8, f: '8am'}
                    , {v: 9, f: '9am'}, {v: 10, f: '10am'}, {v: 11, f: '11am'}, {v: 12, f: '12pm'}, {v: 13, f: '1pm'}, {v: 14, f: '2pm'}, {v: 15, f: '3pm'}, {v: 16, f: '4pm'}
                    , {v: 17, f: '5pm'}, {v: 18, f: '6pm'}, {v: 19, f: '7pm'}, {v: 20, f: '8pm'}, {v: 21, f: '9pm'}, {v: 22, f: '10pm'}, {v: 23, f: '11pm'}],
                title: "Time of Event"}
        };
        chart.draw(data, options);
    }

    function changeDescription(deviceID) {
        var newDescription = prompt("Enter new desription:", "New description");
        if (newDescription != null) {
            $.post("controller.php", {action: "ChangeDescription", description: newDescription, ID: deviceID}, setTimeout(function () {
                location.reload();
            }, 500));
        }
    }
</script>



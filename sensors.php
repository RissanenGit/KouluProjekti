<script>
    function removeDevice(deviceID) {
        if (confirm("Remove Device with ID: " + deviceID) == true) {
            //window.location = "controller.php?action=RemoveDevice&ID=" + deviceID;
            $.post("controller.php", {action: "RemoveDevice", ID: deviceID}, setTimeout(function () {
                location.reload();
            }, 500));

        }
    }
    function renameDevice(deviceID) {
        var deviceName = prompt("Enter new name for the device", "DeviceName");
        if (deviceName != null) {
            $.post("controller.php", {action: "RenameDevice", name: deviceName, ID: deviceID}, setTimeout(function () {
                location.reload();
            }, 500));
        }
    }
    function authorizeDevice(deviceID) {
        if (confirm("Authorize device with ID: " + deviceID) == true) {
            $.post("controller.php", {action: "AuthorizeDevice", ID: deviceID}, setTimeout(function () {
                location.reload();
            }, 500));
        }
    }

    function showDetails(deviceID) {
        window.location = "index.php?site=details&ID=" + deviceID + "&time=day";
    }
    function showHelp() {
        $("#hiddenHelp").show();
    }

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once 'PHP/database.php';

$db = new Database();
if (!isset($_SESSION['timeout'])) {
    die();
} else {
    ?>
    <div class="taulukkoDiv">
        <div class ="well nospace editedWell">
            <h3 class="page-header">Listed Devices</h3>
            <div class="well nospace table-responsive">
                <table class="table table-hover tableMarginZero">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Device ID</th>
                            <th class="text-center">Battery Status</th>
                            <th>Last Update</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = $db->GetAcceptedDevices(TRUE);
                        foreach ($data as $variable) {
                            echo '
                <tr>
                    <td onclick="showDetails(' . $variable["idDevice"] . ')" style="cursor: pointer;">' . htmlspecialchars($variable["Name"]) . '</td>
                    <td class="text-center">' . $variable["idDevice"] . '</td>
                    <td class="text-center">' . $variable["BatteryLevel"] . '</td>   
                    <td>' . $variable["LastUpdate"] . '</td>
                    <td>';
                            if ($_SESSION["user"]["permission"] == 1) {
                                echo'
                    <span class="glyphicon glyphicon-edit text-success actionNappi" title="Rename device" onclick="renameDevice(' . $variable["idDevice"] . ')"></span>
                    <span class="glyphicon glyphicon-remove-circle text-danger actionNappi" title="Remove device" onclick="removeDevice(' . $variable["idDevice"] . ')"></span>
                    ';
                            }
                        }
                        ?>
                        </td>  
                        </tr>
                    </tbody>
                </table>
            </div>
            <span class="glyphicon glyphicon-info-sign text-success infoButton" data-toggle="tooltip" data-placement="right" title="Click on the device name to see more detailed info"></span>
        </div>
        <!--Laitteet joita ei ole vielä hyväksytty-->
        <div class ="well nospace editedWell" style="margin-top: 20px;">
            <h3 class="page-header">Unauthorized Devices</h3>
            <div id="tableBottomSpace">
                <div class="well nospace table-responsive">
                    <table class="table table-hover tableMarginZero">
                        <thead>
                            <tr>
                                <th>Device ID</th>
                                <th>Date Added</th>
                                <th>Actions</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $data = $db->GetAcceptedDevices(FALSE);
                            foreach ($data as $variable) {
                                echo '
                        <tr>
                            <td>' . $variable["idDevice"] . '</td>
                            <td>' . $variable["LastUpdate"] . '</td>
                            <td>';
                                if ($_SESSION["user"]["permission"] == 1) {
                                    echo'<span class="glyphicon glyphicon-ok-circle text-success actionNappi" title="Authorize device" onclick="authorizeDevice(' . $variable["idDevice"] . ')"></span>
                            <span class="glyphicon glyphicon-remove-circle text-danger actionNappi" title="Remove device" onclick="removeDevice(' . $variable["idDevice"] . ')"></span>';
                                }
                            }
                            ?>
                            </td>  
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
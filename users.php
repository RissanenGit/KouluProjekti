<script>
    function removeUser(userID) {
        if (confirm("Remove User with ID: " + userID) == true) {
            $.post("controller.php", {action: "RemoveUser", ID: userID}, setTimeout(function () {
                location.reload();
            }, 500));

        }

    }

    function changeUsername(userID) {
        var userName = prompt("Enter a new username:");
        if (userName != null) {
            $.post("controller.php", {action: "ChangeUsername", username: userName, ID: userID}, setTimeout(function () {
                location.reload();
            }, 500));
        }
    }

    function changePassword(userID) {
        var passWord = prompt("Enter a new password:");
        if (passWord != null) {
            $.post("controller.php", {action: "ChangePassword", password: passWord, ID: userID}, setTimeout(function () {
                location.reload();
            }, 500));
        }
    }

    function addUser() {
        var userName = document.getElementById("username").value;
        var passWord = document.getElementById("password").value;
        var Permission = 0;
        if (document.getElementById("checkbox").checked == true) {
            Permission = 1;
        } else {
            Permission = 0;
        }
        $.post("controller.php", {action: "AddUser", username: userName, password: passWord, permission: Permission}, setTimeout(function () {
            location.reload();
        }, 500));
    }

    function showAddUser() {
        if ($(".addUserDiv").is(":hidden")) {
            $(".addUserDiv").show();
            $("#userButton").text("Close");
        } else {
            $(".addUserDiv").hide();
            $("#userButton").text("Add User");
        }
    }
</script>

<?php
require_once 'PHP/database.php';

$db = new Database();
if (!isset($_SESSION['timeout'])) {
    die();
} else {
    ?>
    <div class="taulukkoDiv">
        <div class ="well nospace editedWell">
            <h3 class="page-header">Users</h3>
            <div class="well nospace table-responsive">
                <table class="table table-hover tableMarginZero">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Full permission</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $userData = $db->GetUsers();
                        foreach ($userData as $variable) {
                    echo '
                    <tr>
                    <td>' . $variable["idUser"] . '</td>
                    <td>' . htmlspecialchars($variable["Username"]) . '</td>
                    <td>';
                    if($variable["Permission"] == 1){
                        echo 'True';
                    }
                    else{
                        echo 'False';
                    }
                    echo'</td>';
                            if ($variable["idUser"] > 1 && $_SESSION['user']['permission'] == 1 || $variable["idUser"] == $_SESSION['user']["id"] ) {
                    echo'
                    <td>
                    <span class="glyphicon glyphicon-edit text-success actionNappi" title="Change Username" onclick="changeUsername(' . $variable["idUser"] . ')"></span>
                    <span class="glyphicon glyphicon-edit text-success actionNappi" title="Change password" onclick="changePassword(' . $variable["idUser"] . ')"></span>';
                                if ($_SESSION['user']['id'] != $variable["idUser"]) {
                                    echo'<span class="glyphicon glyphicon-remove-circle text-danger actionNappi" title="Remove User" onclick="removeUser(' . $variable["idUser"] . ')"></span>';
                                }
                    echo'</td></tr>';
                            } 
                    else {
                    echo '
                    <td>
                    </td>
                        </tr>'; }}?>
                        </tbody>
                    </table>
                </div>
            <?php 
                if($_SESSION['user']["id"] == 1) //Vain admin voi lisätä käyttäjiä
                {
                    echo'
                <div class="well nospace table-responsive addUserDiv">
                    <table class="table table-hover tableMarginZero">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Full permission</th>
                                <th>Confirm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" autocomplete="off" id="username">
                                </td>
                                <td>
                                    <input type="password" autocomplete="off" id="password">
                                </td>
                                <td>
                                    <input type="checkbox" id="checkbox">
                                </td>
                                <td>
                                    <input type ="button" value="Add User" onclick="addUser()">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-default" id ="userButton" style="margin-top: 30px;" onclick="showAddUser()">Add User</button>
                ';}?>
            </div>
        </div>
    <?php } ?>

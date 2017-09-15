<?php

error_reporting(-1);
ini_set('display_errors', 'On');

session_start();
require_once 'PHP/database.php';
$db = new Database();
if (!isset($_SESSION['timeout'])) {
    if ($_POST["action"] != "LogIn") {
        die();
    }
}
$action = $_POST["action"];
if (strlen($action) == 0) {
    $action = $_GET["action"];
    if ($action == "LogOut") {
        $db->Logout_User();
    }
    sendBack();
}
switch ($action) {

    case "RemoveDevice":
        $deviceId = $_POST["ID"];
        $db->RemoveDevice($deviceId);
        break;

    case "RenameDevice":
        $deviceName = $_POST["name"];
        $deviceId = $_POST["ID"];
        if (checkLength($deviceName)) {
            $db->RenameDevice($deviceId, $deviceName);
        }

        break;

    case "AuthorizeDevice":
        $deviceId = $_POST["ID"];
        $db->AuthorizeDevice($deviceId);
        break;

    case "ChangeDescription":
        $deviceId = $_POST["ID"];
        $description = $_POST["description"];
        if (checkLength($description)) {
            $db->ChangeDescription($deviceId, $description);
        }

        break;

    case "AddUser":
        $username = $_POST["username"];
        $password = $_POST["password"];
        $permission = $_POST["permission"];
        if (checkLength($username) && checkLength($password)) {
            $db->AddUser($username, $password, $permission);
        }
        break;
    case "RemoveUser":
        $userId = $_POST["ID"];
        $db->RemoveUser($userId);
        break;

    case "ChangeUsername":
        $userId = $_POST["ID"];
        $username = $_POST["username"];
        if (checkLength($username)) {
            $db->ChangeUsername($userId, $username);
        }

        break;
    case "ChangePassword":
        $userId = $_POST["ID"];
        $password = $_POST["password"];
        if (checkLength($password)) {
            $db->ChangePassword($userId, $password);
        }

        break;
    case "LogIn":
        $username = $_POST["username"];
        $password = $_POST["password"];
        $db->Login_User($username, $password);
        break;
}
sendBack();

function checkLength($input) {
    if (strlen($input) > 0) {
        return True;
    }
    return False;
}

function sendBack() {
    header("Location:" . $_SERVER["HTTP_REFERER"]);
    die();
}

?>

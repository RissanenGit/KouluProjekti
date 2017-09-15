<?php

class Database {

    private $dbIp, $dbPasswd, $dbPort, $dbName, $dbUser;

    function __construct() {
        include('dbuser.php');
        $this->dbIp = $_dbhost;
        $this->dbPasswd = $_dbpass;
        $this->dbName = $_db;
        $this->dbUser = $_dbuser;
    }

    public function Connect() {
        try {
            $c = new PDO("mysql:host={$this->dbIp};dbname={$this->dbName};charset=utf8", $this->dbUser, $this->dbPasswd);
            $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $c;
        } catch (PDOException $e) {
            echo $e;
            return NULL;
        }
    }

    public function FetchData($query, $query_params, $dbConnection) {
        try {
            $stmt = $dbConnection->prepare($query);

            if (count($query_params) > 0)
                $stmt->execute($query_params);
            else
                $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e;
            //die();
            return NULL;
        }
    }

    //For non returning queries
    public function RunQuery($query, $query_params, $dbConnection) {
        try {
            $stmt = $dbConnection->prepare($query);

            if (count($query_params) > 0)
                $result = $stmt->execute($query_params);
            else
                $result = $stmt->execute();
            return $result;
        } catch (PDOException $e) {

            //echo $e;
            return NULL;
        }
    }

    public function Login_User($username, $password) {

        $result = $this->FetchData("select * from User where Username = :username", array(":username" => $username), $this->Connect());
        if (hash("sha256", $password) === $result[0]['Password']) {
            session_start();
            session_regenerate_id(true);
            $_SESSION['user']['id'] = $result[0]['idUser'];
            $_SESSION['user']['username'] = $result[0]['Username'];
            $_SESSION['user']['permission'] = $result[0]['Permission'];
        }
    }

    public function Logout_User() {
        session_start();
        session_destroy();
    }

    //Laite juttuja
    public function GetAcceptedDevices($accepted) {
        if ($accepted) {
            return $this->FetchData("select * from Device where Accepted = true", array(), $this->Connect());
        }
        return $this->FetchData("select * from Device where Accepted = false", array(), $this->Connect());
    }

    public function RemoveDevice($deviceID) {
        $this->RunQuery("delete from Device where idDevice = :ID", array(":ID" => $deviceID), $this->Connect());
    }

    public function RenameDevice($deviceID, $deviceName) {
        $this->RunQuery("update Device set Name = :NAME where idDevice = :ID", array(":NAME" => $deviceName, ":ID" => $deviceID), $this->Connect());
    }

    public function AuthorizeDevice($deviceID) {
        $this->RunQuery("update Device set Accepted = true where idDevice = :ID", array(":ID" => $deviceID), $this->Connect());
    }

    public function GetDeviceDetails($deviceID) {
        return $this->FetchData("select idDevice, Name, Description, BatteryLevel, RegisterDate from Device where idDevice = :ID", array(":ID" => $deviceID), $this->Connect());
    }

    public function ChangeDescription($deviceID, $description) {
        $this->RunQuery("update Device set Description = :DESCRIPTION where idDevice = :ID", array(":ID" => $deviceID, ":DESCRIPTION" => $description), $this->Connect());
    }

    //Kayttaja juttuja
    public function GetUsers() {
        return $this->FetchData("select idUser, Username, Permission from User", array(), $this->Connect());
    }

    public function RemoveUser($userID) {
        if ($userID > 1) {
            $this->RunQuery("delete from User where idUser = :ID", array(":ID" => $userID), $this->Connect());
        }
    }

    public function AddUser($userName, $passWord, $permission) {
        $this->RunQuery("insert into User (Username, Password, Permission) values(:USERNAME,:PASSWORD,:PERMISSION)", array(":USERNAME" => $userName, ":PASSWORD" => hash("sha256", $passWord), ":PERMISSION" => $permission), $this->Connect());
    }

    public function ChangeUsername($userID, $username) {
        $this->RunQuery("update User set Username = :USERNAME where idUser = :ID", array(":USERNAME" => $username, ":ID" => $userID), $this->Connect());
    }

    public function ChangePassword($userID, $password) {
        $this->RunQuery("update User set Password = :PASSWORD where idUser = :ID", array(":PASSWORD" => $password, ":ID" => $userID), $this->Connect());
    }

    public function GetDeviceLogs($deviceID, $order,$orderBy){
        $data = $this->FetchData("select LogDate, Level, GestureData, ProximityData, AmbientData from DeviceLog where idDevice = :ID order by :ORDER :DIR", array(":ID"=>$deviceID,":ORDER"=>$order,":DIR"=>$orderBy), $this->Connect());
        if($data == ""){
            echo "NotValidData";
            return $this->FetchData("select LogDate, Level, GestureData, ProximityData, AmbientData from DeviceLog where idDevice = :ID", array(":ID"=>$deviceID), $this->Connect());
        }
        else{
            echo "ValidData";
            return $data;
        }
        /*
           $orderString = "";
           switch($order){
               case "date":
                   $orderString = "order by LogDate";
                   break;
               case "level":
                   $orderString = "order by Level";
                   break;
               case "gesture":
                   $orderString = "order by GestureData";
                   break;
               case "proximity":
                   $orderString = "order by ProximityData";
                   break;
               case "ambient":
                   $orderString = "order by AmbientData";
                   break;
               default:
                   return $this->FetchData("select LogDate, Level, GestureData, ProximityData, AmbientData from DeviceLog where idDevice = :ID", array(":ID"=>$deviceID), $this->Connect());
           }
           $orderDir = "";
           if ($orderBy == "asc"){
               $orderDir = $orderBy;
           }   
           else if($orderBy == "desc"){
                $orderDir = $orderBy;
           }
           else{
               return $this->FetchData("select LogDate, Level, GestureData, ProximityData, AmbientData from DeviceLog where idDevice = :ID", array(":ID"=>$deviceID), $this->Connect());
           }

        return $this->FetchData("select LogDate, Level, GestureData, ProximityData, AmbientData from DeviceLog where idDevice = :ID :ORDER :DIR", array(":ID"=>$deviceID,":ORDER"=>$orderString,":DIR"=>$orderDir), $this->Connect());
          */
    }
    public function GetDeviceActivity($deviceID, $time) {
  
        $variable = time();
            if($time == "day"){
                $variable -= 3600 * 24;
            }
            else if($time == "week"){
                $variable -= 3600 * 24 * 7;
            }
            else if($time == "month"){
                $variable -= 3600 * 24 * 30;
            }
            
           $variable = date("Y-m-d H:i:s",$variable);
           return $this->FetchData("select count(*) as Occurences, LogDate as Time from DeviceLog where idDevice = :ID and LogDate >= :DATE group by date_format(LogDate,'%H');", array(":ID" => $deviceID,":DATE"=>$variable), $this->Connect());
           
           
    }
    


}

?>
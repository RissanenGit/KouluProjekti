<?php

require_once 'PHP/database.php';
$db = new Database();
$activityData = $db->GetDeviceActivity(99); // Activity data
/*
$rows = array();
$cols = array(
    array("id" => "", "label" => "Hour", "pattern" => "", "type" => "date"),
    array("id" => "", "label" => "Amount", "pattern" => "", "type" => "number")
);

foreach ($activityData as $data) {
    array_push($rows, array("c" => array(array("v" => $data["Time"], "f" => null), array("v" => $data["Occurences"], "f" => null))));
}
$table = array();

$table["cols"] = $cols;
$table["rows"] = $rows;

$jsonTable = json_encode($table);

//echo $jsonTable;
date_default_timezone_set("Europe/Helsinki");
$variable = time();
      
                //$variable -= 3600 * 24;
            
          
               // $variable -= 3600 * 24 * 7;
            
        
                $variable -= 3600 * 24 * 30;
            
           
            $variable = date("Y-m-d H:i:s",$variable);
echo $variable;*/


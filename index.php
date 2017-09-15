<!DOCTYPE html>
<?php
$button1 = "Log Out";
$button2 = "Users";
$button3 = "Listed Devices";
$button4 = "About";

$site = "login";

if (!empty($_GET['site']))
    $site = $_GET['site'];
else {
    $site = $button4;
}
session_start();
$time = time();
if (isset($_SESSION['timeout']) && $time > $_SESSION['timeout']) {
    session_unset();
    session_destroy();
    $site = "login";
} else if (!isset($_SESSION['timeout'])) {
    $site = "login";
}

$siteArray = array(
    array($button2, "index.php?site=$button2", "users.php"),
    array($button3, "index.php?site=$button3", "sensors.php"),
    array($button4, "index.php?site=$button4", "about.php"),
    array($button1, "controller.php?action=LogOut", 'about.php'),
);
?>


<html>
    <head>
        <title>Shibale</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="Expires" content="0"/>

  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!--<link rel="stylesheet" href="http://getbootstrap.com.vn/examples/equal-height-columns/equal-height-columns.css" />-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <script src="script.js"></script>



        <?php if ($site == "login"): ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#myModal').modal('show');
                });
            </script>
        <?php endif; ?>
    </head>
    <body>
        <div class = "row nospace">
            <div class = "palkki col-sm-12 nospace">
                <h1 id="rottisTeksti">Rottis kutonen</h1>
            </div>
        </div>
        <div class = "row row-100 nospace">
            <nav class ="col-sm-2 colBg">
                <ul class="nav nav-pills nav-stacked lista">
                    <?php
                    foreach ($siteArray as $value) {
                        if ($site == $value[0]) {
                            echo'<li class="active"><a href="' . $value[1] . '">' . $value[0] . '</a></li>';
                        }
                        else if($site =="details" && $value[0] == "Listed Devices"){
                            echo'<li class="active"><a href="' . $value[1] . '">' . $value[0] . '</a></li>';
                        }
                        else {
                            echo'<li class="inactive"><a href="' . $value[1] . '">' . $value[0] . '</a></li>';
                        }
                    }
                    ?>

                </ul>
            </nav>
            <div class="col-sm-10 colBg">
                <div class="contentDiv">
                    <?php
                    foreach ($siteArray as $value) {
                        if ($site == $value[0]) {
                            include $value[2];
                            break;
                        } else if ($site == "login") {
                            include "login.php";
                            break;
                        } else if ($site == "details") {
                            include "details.php";
                            break;
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
        <!--
        <div class = "row nospace colBg">
            <div class="col-sm-12">
                <div class="footerDiv">
                    <b>ASDSADSD</b>
                    <b>LOLLOLOL</b>
                    <b>HONHONHON</b>
                </div>
            </div>

        </div>-->
    </body>
</html>

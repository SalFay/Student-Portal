<?php
ob_start();
session_start();
require 'config.php';
require 'inc/loader.php';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Student Portal</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="content/theme/css/bootstrap.min.css">
        <link rel="stylesheet" href="content/theme/css/icomoon-social.css">
        <link rel="stylesheet" href="content/assets/chatbox.css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="content/theme/css/leaflet.css" />
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/leaflet.ie.css" />
        <![endif]-->
        <link rel="stylesheet" href="content/theme/css/main.css">

        <script src="content/theme/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->


        <!-- Navigation & Logo-->
        <div class="mainmenu-wrapper">
            <div class="container hidden-print">
                <div class="menuextras">
                    <div class="extras">
                        <ul>
                            <?php
                            if(empty($_SESSION['std_name'])){
                                ?>
                                <li><a href="login.php"><i class="glyphicon glyphicon-log-in"></i> Login</a></li>
                            <?php
                            }else{
                                ?>
                                <li><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                            <?php
                            }
                            ?>
                            <li><a href="admin"><i class="glyphicon glyphicon-log-in"></i> Admin Login</a></li>
                        </ul>
                    </div>
                </div>
                <nav id="mainmenu" class="mainmenu">
                    <ul>
                        <li class="logo-wrapper"><a href="index.html"><img src="content/theme/img/mPurpose-logo.png" alt="Multipurpose Twitter Bootstrap Template"></a></li>
                        <?php
                        $db = new Database();
                        $db->query("SELECT * FROM menus ORDER BY menu_order ASC");
                        while($r = $db->fetchObject()){
                            echo "<li><a href='$r->menu_link' title='$r->menu_title'>$r->menu_label</a>
                        </li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>

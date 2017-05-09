<?php
ob_start();
require '../config.php';
require '../inc/loader.php';
session_start();
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["user_level"])) {
    header("location:login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Portal</title>
    <link href="https://bootswatch.com/paper/bootstrap.min.css" rel="stylesheet">
    <link href="../content/assets/admin-theme/css/style.css" rel="stylesheet">
    <link href="../content/assets/chatbox.css" rel="stylesheet">
    <script type="text/javascript" src="../content/assets/plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: ".editor",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons",
            image_advtab: true
        });
    </script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="admin-body">
<nav class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">AdminCP</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php"><i class="glyphicon glyphicon-home"></i> Home </a></li>
                <?php
                if ($_SESSION["user_level"] == "admin") {
                    ?>

                    <li><a href="pages.php"><i class="glyphicon glyphicon-list"></i> Pages </a></li>
                    <li><a href="menus.php"><i class="glyphicon glyphicon-list-alt"></i> Menus </a></li>
                    <?php
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                                class="glyphicon glyphicon-user"></i> Welcome <?php echo $_SESSION["user_name"] ?> <span
                                class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="profile.php">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="login.php?do=logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
    <section class="row clearfix">
        <div class="col-sm-3 hidden-xs">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Quick Links
                    </div>
                </div>
                <div class="list-group">
                    <a href="departments.php" class="list-group-item">Manage Departments</a>
                    <a href="courses.php" class="list-group-item">Manage Courses</a>
                    <a href="admit-student.php" class="list-group-item">Admit Students</a>
                    <?php
                    if ($_SESSION["user_level"] == "admin") {
                        ?>
                        <a href="admit-teachers.php" class="list-group-item">Admit Teachers</a>
                        <?php
                    }
                    ?>
                    <a href="announcements.php" class="list-group-item">Announcements</a>
                    <a href="exams.php" class="list-group-item">Manage Exams</a>
                    <a href="test.php" class="list-group-item">Manage Test</a>
                    <a href="students.php" class="list-group-item">Manage Students</a>
                    <a href="assignments.php" class="list-group-item">Manage Assignments</a>
                    <?php
                    if ($_SESSION["user_level"] == "admin") {
                        ?>
                        <a href="teachers.php" class="list-group-item">Manage Teachers</a>
                        <a href="users.php" class="list-group-item">Manage Users</a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-xs-12">

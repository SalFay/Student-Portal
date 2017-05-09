<?php
require '../config.php';
require '../inc/loader.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <link href="../content/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../content/assets/admin-theme/css/style.css" rel="stylesheet">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="login-body">
        <div class="container-fluid">
            <section class="row clearfix login-box">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="panel panel-login">
                        <div class="panel-heading">
                            <div class="panel-title text-center text-capitalize">
                                Login
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                                if(isset($_POST["login"])){
                                    $name = cleanString($_POST["username"]);
                                    $password = cleanString($_POST["password"]);
                                    if(empty($name) || empty($password)){
                                        msgBox("alert", "All Fields are Required");
                                    }else{
                                        $password = md5($password);
                                        $sql = "SELECT * FROM users WHERE user_login = ? AND user_password = ?";
                                        $db = new Database();
                                        try{
                                            $args = array($name,$password);
                                            $db->query($sql,$args);
                                            if($db->rowCount()>0){
                                                $r = $db->fetchObject();
                                                $_SESSION["user_id"] = $r->user_id;
                                                $_SESSION["user_name"] = $r->user_name;
                                                $_SESSION["user_level"] = $r->user_level;
                                                header("location:index.php");
                                                exit();
                                            }else{
                                                msgBox("error", "Invalid Login details");
                                            }
                                        }catch(PDOException $e){
                                            msgBox("error", $e->getMessage());
                                        }
                                    }
                                }else if(isset ($_GET["do"])){
                                    $do = $_GET["do"];
                                    if($do == "logout"){
                                        session_destroy();
                                        session_unset();
                                        header("location:login.php");
                                        exit();
                                    }
                                }else if(isset($_SESSION["user_id"])&& isset ($_SESSION["user_level"])){
                                    header("location:index.php");
                                    exit();
                                }
                            ?>
                            <form method="post" action="login.php" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Username: </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="username" autofocus="" required="" placeholder="e.g Waqas" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Password: </label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password" autofocus="" required="" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <input type="reset" class="btn btn-lg btn-warning" value="Clear" />
                                    <input type="submit" class="btn btn-info btn-lg" name="login" value="Login" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            
            
            <footer class="row clearfix">
                <div class="col-xs-12">
                    <p class="text-muted small text-center">Copyright &copy; 2017 Student Portal Team</p>
                </div>
            </footer>
        </div><!-- // Container Fluid -->

        <script src="../content/assets/plugins/jquery.js"></script>
        <script src="../content/assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

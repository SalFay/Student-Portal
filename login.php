<?php require 'header.php'; ?>

        <!-- Page Title -->
        <div class="section section-breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Login</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="basic-login">
                            <?php
                                if(isset($_POST["login"])){
                                    $name = cleanString($_POST["username"]);
                                    $pass = cleanString($_POST["password"]);
                                    if(empty($name)||empty($pass)){
                                        msgBox("error", "All fields are required");
                                    }else{
                                        $db = new Database();
                                        $pass = md5($pass);
                                        $stmt = "SELECT * FROM users WHERE user_level = 'student' AND user_login = ? AND user_password = ?";
                                        try{
                                            $db->query($stmt,[$name,$pass]);
                                            if($db->rowCount()>0){
                                                $r = $db->fetchObject();
                                                $_SESSION["std_id"] = $r->user_id;
                                                $_SESSION["std_name"] = $r->user_name;
                                                header("location:dashboard.php");
                                                exit();
                                            }else{
                                                msgBox("error", "Invalid login details");
                                            }
                                        }catch(PDOException $e){
                                            msgBox("error", $e->getMessage());
                                        }
                                    }
                                }
                            ?>
                            <form method="post" action="login.php">
                                <div class="form-group">
                                    <label><i class="icon-user"></i> <b>Username</b></label>
                                    <input class="form-control" type="text" name="username">
                                </div>
                                <div class="form-group">
                                    <label for="login-password"><i class="icon-lock"></i> <b>Password</b></label>
                                    <input class="form-control" name="password" type="password" placeholder="">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="login" value="Login" class="btn pull-right" />
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--<div class="col-sm-7 social-login">
                        <p>Or login with your Facebook or Twitter</p>
                        <div class="social-login-buttons">
                            <a href="#" class="btn-facebook-login">Login with Facebook</a>
                            <a href="#" class="btn-twitter-login">Login with Twitter</a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="not-member">
                            <p>Not a member? <a href="page-register.html">Register here</a></p>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>

<?php require 'footer.php'; ?>
<?php require 'header.php' ?>
<?php
$id = (int) $_SESSION["user_id"];
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            Profile
        </div>
    </div>
    <div class="panel-body">
        <?php
        $r = "";
        $db = new Database();
        $stmt = "SELECT * FROM users WHERE user_id = ?";
        try {
            $db->query($stmt, array($id));
            if ($db->rowCount() > 0) {
                $r = $db->fetchObject();
            }
        } catch (PDOException $e) {
            msgBox("error", $e->getMessage());
        }

        if (isset($_POST["update"])) {
            $login = cleanString($_POST["ulogin"]);
            $opass = cleanString($_POST["opass"]);
            $npass = cleanString($_POST["npass"]);
            $cpass = cleanString($_POST["cpass"]);
            $email = cleanString($_POST["uemail"]);
            $name = cleanString($_POST["uname"]);

            if (empty($login) || empty($opass) || empty($email) || empty($name)) {
                msgBox("error", "All Fields are required");
            } else if (!empty($npass) && ($npass !== $cpass)) {
                msgBox("error", "New password must matche");
            } else {
                $opass = md5($opass);
                $db->query("SELECT user_id FROM users WHERE user_password = ? AND user_id = ?", array($opass, $id));
                if ($db->rowCount() > 0) {
                    $new_password = $opass;
                    if (!empty($npass) && $npass === $cpass) {
                        $new_password = md5($npass);
                    }
                    try {
                        $stmt = "UPDATE users SET user_login = ?,"
                                . "user_password = ?, user_email = ?,"
                                . "user_name = ? WHERE user_id = ?";
                        $args = array($login, $new_password, $email, $name, $id);
                        $db->runQuery($stmt, $args);
                        msgBox("ok", "Profile Updated");
                    } catch (PDOException $e) {
                        msgBox("error", $e->getMessage());
                    }
                }else{
                    msgBox("error", "Invalid Old Password");
                }
            }
        }
        ?>
        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="ulogin" class="form-control" required="" value="<?php echo $r->user_login ?>" />
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="opass" class="form-control" required="" />
                <p class="help-block">To change your current password, just fill out below fields with new password</p>
            </div>
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="npass" class="form-control" />
            </div>
            <div class="form-group">
                <label>Confirm New Password:</label>
                <input type="password" name="cpass" class="form-control" />
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="uname" class="form-control" required="" value="<?php echo $r->user_name ?>" />
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="uemail" class="form-control" required="" value="<?php echo $r->user_email ?>" />
            </div>
            <input type="submit" name="update" class="btn btn-primary" value="Update"/>
        </form>
    </div>
</div>
<?php require 'footer.php' ?>
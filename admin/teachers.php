<?php require 'header.php' ?>
<?php
if ($_SESSION["user_level"] !== "admin") {
    die(msgBox("error", "WHat are you looking for?"));
}
$db = new Database();
if (isset($_GET["action"])) {
    $a = cleanString($_GET["action"]);
    if ($a === "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Teacher
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $login = cleanString($_POST["login"]);
                    $password = cleanString($_POST["password"]);
                    $email = cleanString($_POST["email"]);
                    $name = cleanString($_POST["name"]);
                    $address = cleanString($_POST["address"]);
                    $contact = cleanString($_POST["contact"]);
                    $qualification = cleanString($_POST["qualification"]);
                    $dob = cleanString($_POST["dob"]);
                    $gender = cleanString($_POST["gender"]);
                    $level = "teacher";
                    if (empty($login) || empty($email) || empty($name) || empty($address) || empty($contact) || empty($qualification) || empty($dob) || empty($gender) || empty($level)) {
                        msgBox("error", "All fields are required");
                    } else {
                        try {
                            $password = md5($password);
                            $dob = date("Y-m-d", strtotime($dob));
                            $stmt = "INSERT INTO users VALUES(null,?,?,?,?,?,?,?,?,?,?)";
                            $args = array($login, $password, $email, $name, $level, $dob, $qualification, $address, $contact, $gender);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Teacher Added");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="teachers.php?action=add">
                    <div class="form-group">
                        <label>Login</label>
                        <input type="text" name="login" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Contact</label>
                        <input type="text" name="contact" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Qualification</label>
                        <input type="text" name="qualification" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <?php echo list_gender(); ?>
                        </select>
                    </div>
                    <input type="submit" name="publish" value="Add" class="btn btn-primary" />
                </form>
            </div>
        </div>
        <?php
    } else if ($a === "edit") {
        if (isset($_GET["id"])) {
            $id = intval(cleanString($_GET["id"]));
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Edit Teacher
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST["publish"])) {
                        $login = cleanString($_POST["login"]);
                        $password = cleanString($_POST["password"]);
                        $email = cleanString($_POST["email"]);
                        $name = cleanString($_POST["name"]);
                        $address = cleanString($_POST["address"]);
                        $contact = cleanString($_POST["contact"]);
                        $qualification = cleanString($_POST["qualification"]);
                        $dob = cleanString($_POST["dob"]);
                        $gender = cleanString($_POST["gender"]);
                        $level = "teacher";
                        if (empty($login) || empty($email) || empty($name) || empty($address) || empty($contact) || empty($qualification) || empty($dob) || empty($gender) || empty($level)) {
                            msgBox("error", "All fields are required");
                        } else {
                            try {
                                $password = md5($password);
                                $dob = date("Y-m-d", strtotime($dob));
                                $stmt = "UPDATE users SET user_login = ?,"
                                        . "user_password = ?, user_email = ?, "
                                        . "user_name = ?, user_level = ?, "
                                        . "user_dob = ?, user_qualification = ?,"
                                        . "user_address = ?, user_contact = ?,"
                                        . "user_gender = ? WHERE user_id = ?";
                                $args = array($login, $password, $email, $name, $level, $dob, $qualification, $address, $contact, $gender, $id);
                                $db->runQuery($stmt, $args);
                                msgBox("ok", "Teacher Updated");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                        }
                    }
                    $db->query("SELECT * FROM users WHERE user_id = ?",array($id));
                    $r = $db->fetchObject();
                    ?>
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                        <div class="form-group">
                            <label>Login</label>
                            <input type="text" name="login" value="<?php echo $r->user_login ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" name="email" value="<?php echo $r->user_email ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="<?php echo $r->user_name ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?php echo $r->user_dob ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Contact</label>
                            <input type="text" name="contact" value="<?php echo $r->user_contact ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Qualification</label>
                            <input type="text" name="qualification" value="<?php echo $r->user_qualification ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" value="<?php echo $r->user_address ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <?php echo list_gender($r->user_gender); ?>
                            </select>
                        </div>
                        <input type="submit" name="publish" value="Update" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <?php
        }
    } else if ($a === "delete") {
        if (isset($_GET["id"])) {
            $id = intval(cleanString($_GET["id"]));
            try {
                $db->runQuery("DELETE FROM users WHERE user_id = ?", array($id));
                msgBox("ok", "Teacher Deleted");
                header("refresh:1;url=teachers.php");
            } catch (PDOException $e) {
                msgBox("erorr", $e->getMessage());
                header("refresh:3;url=teachers.php");
            }
        }
    } else {
        echo "Nothing to do";
    }
} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Manage Teachers
                <div class="pull-right">
                    <a href="teachers.php?action=add" class="btn btn-sm btn-success">Add Teacher</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Login</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $db->query("SELECT * FROM users WHERE user_level = 'teacher'");
                    while ($r = $db->fetchObject()) {
                        echo "
                            <tr>
                                <td>$r->user_login</td>
                                <td>$r->user_name</td>
                                <td>$r->user_email</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='teachers.php?action=edit&amp;id=$r->user_id' class='btn btn-primary'>Edit</a>
                                        <a href='teachers.php?action=delete&amp;id=$r->user_id' class='btn btn-danger'>Delete</a>
                                    </div>
                                </td>
                            </tr>
                        ";
                    }
                } catch (PDOException $e) {
                    msgBox("error", $e->getMessage());
                }
                ?>
            </table>
        </div>
    </div>
    <?php
}
?>
<?php require 'footer.php' ?>

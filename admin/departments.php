<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["action"])) {
    $a = cleanString($_GET["action"]);
    if ($a === "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Department
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $name = cleanString($_POST["name"]);
                    $semester = cleanString($_POST["semester"]);
                    $contact = cleanString($_POST["contact"]);
                    if (empty($name) || empty($semester) || empty($contact)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $stmt = "INSERT INTO departments(department_name, department_semester, department_contact) VALUES(?,?,?)";
                            $args = array($name, $semester, $contact);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Department Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="departments.php?action=add">
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="name" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Department Semesters</label>
                        <input type="number" name="semester" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Department Contact</label>
                        <input type="text" name="contact" class="form-control" required="" />
                    </div>
                    <input type="submit" name="publish" value="Publish" class="btn btn-primary" />
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
                        Edit Department
                        <div class="pull-right">
                            <a href="departments.php" class="btn btn-sm btn-success">Back</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST["publish"])) {
                        $name = cleanString($_POST["name"]);
                        $semester = cleanString($_POST["semester"]);
                        $contact = cleanString($_POST["contact"]);
                        if (empty($name) || empty($semester) || empty($contact)) {
                            msgBox("error", "All Fields are required");
                        } else {
                            try {
                                $stmt = "UPDATE departments SET department_name = ?,"
                                        . "department_semester = ?,"
                                        . "department_contact = ? WHERE department_id = ?";
                                $args = array($name, $semester,$contact, $id);
                                $db->runQuery($stmt, $args);
                                msgBox("ok", "Department Updated");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                        }
                    }
                    $db->query("SELECT * FROM departments WHERE department_id = ?", array($id));
                    $r = $db->fetchObject();
                    ?>
                    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
                        <div class="form-group">
                            <label>Department Name</label>
                            <input type="text" name="name" value="<?php echo $r->department_name ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Department Semesters</label>
                            <input type="number" name="semester" value="<?php echo $r->department_semester; ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Department Contact</label>
                            <input type="text" name="contact" value="<?php echo $r->department_contact ?>" class="form-control" required="" />
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
                $db->runQuery("DELETE FROM departments WHERE department_id = ?", array($id));
                msgBox("ok", "Department Deleted");
                header("refresh:1;url=departments.php");
            } catch (PDOException $e) {
                msgBox("erorr", $e->getMessage());
                header("refresh:3;url=departments.php");
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
                Manage Departments
                <div class="pull-right">
                    <a href="departments.php?action=add" class="btn btn-sm btn-success">Add Department</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Name</th>
                    <th>Semesters</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $db->query("SELECT * FROM departments");
                    while ($r = $db->fetchObject()) {
                        echo "
                            <tr>
                                <td>$r->department_name</td>
                                <td>$r->department_semester</td>
                                <td>$r->department_contact</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='' class='btn btn-success'>View</a>
                                        <a href='departments.php?action=edit&amp;id=$r->department_id' class='btn btn-primary'>Edit</a>
                                        <a href='departments.php?action=delete&amp;id=$r->department_id' class='btn btn-danger'>Delete</a>
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

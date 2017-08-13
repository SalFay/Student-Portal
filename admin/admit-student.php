<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["do"])) {
    $do = cleanString($_GET["do"]);
    if ($do == "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Admit Student
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["assign"])) {
                    $student = cleanString($_POST["student"]);
                    $department = cleanString($_POST["department"]);
                    $semester = cleanString($_POST["semester"]);

                    if (empty($student) || empty($department) || empty($semester)) {
                        msgBox("error", "All fields are required");
                    } else {
                        $stmt = "INSERT INTO admission VALUES(null,?,?,?,?,?)";
                        $args = array("student", $student, $department, $semester, 0);
                        try {
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Student Assigned Successfully");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                    <div class="form-group">
                        <label>Student</label>
                        <select name="student" class="form-control">
                            <?php echo list_student(); ?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control">
                            <?php echo list_departments(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control">
                            <?php echo list_semesters(); ?>
                        </select>
                    </div>
                    <input type="submit" name="assign" value="Admit Student" class="btn btn-primary" />
                </form>
            </div>
        </div>
        <?php
    } else if ($do == "edit") {
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Admit Student
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST["assign"])) {
                        $student = cleanString($_POST["student"]);
                        $department = cleanString($_POST["department"]);
                        $semester = cleanString($_POST["semester"]);

                        if (empty($student) || empty($department) || empty($semester)) {
                            msgBox("error", "All fields are required");
                        } else {
                            $stmt = "UPDATE admission SET department_id = ?,"
                                    . "user_id = ?, course_id = ?, semester_id = ? "
                                    . "WHERE admit_id = ?";
                            $args = array($department,$student, $course,$semester, $id);
                            try {
                                $db->runQuery($stmt, $args);
                                msgBox("ok", "Student Assigned Successfully");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                        }
                    }
                    $db->query("SELECT * FROM admission WHERE admit_id = ?",[$id]);
                    $r = $db->fetchObject();
                    ?>
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                        <div class="form-group">
                            <label>Student</label>
                            <select name="student" class="form-control">
                                <?php echo list_student($r->user_id); ?> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-control">
                                <?php echo list_departments($r->department_id); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <select name="semester" class="form-control">
                                <?php echo list_semesters($r->semester_id); ?>
                            </select>
                        </div>
                        <input type="submit" name="assign" value="Admit Student" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <?php
        }
    } else if ($do == "delete") {
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
            $db->runQuery("DELETE FROM admission WHERE admit_id = ?", $id);
            msgBox("ok", "Deleted");
            header("refresh:1;location:admit-students.php");
        }
    }
} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Admitted Students
                <div class="pull-right">
                    <a href="admit-student.php?do=add" class="btn btn-success btn-xs">
                        Admit Students
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Action</th>
                </tr>
                <?php
                $db->query("SELECT * FROM admission WHERE admit_type = 'student'");
                while ($r = $db->fetchObject()) {
                    $user_name = $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $r->user_id);
                    $department = $db->queryValues("SELECT department_name FROM departments WHERE department_id = ?", "department_name", $r->department_id);
                    echo "
                    <tr>
                        <td>$user_name->user_name</td>
                        <td>$department->department_name</td>
                        <td>$r->semester_id</td>
                        <td>
                            <div class='btn-group'>
                                <a href='admit-student.php?do=edit&amp;id=$r->admit_id' class='btn btn-primary'>Edit</a>
                                <a href='admit-student.php?do=delete&amp;id=$r->admit_id' class='btn btn-danger'>Delete</a>
                            </div>
                        </td>
                    </tr>
                ";
                }
                ?>
            </table>
        </div>
    </div>
    <?php
}
?>
<?php require 'footer.php' ?>

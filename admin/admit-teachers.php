<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["do"])) {
    $do = cleanString($_GET["do"]);
    if($do=="add"){
        ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Assign Teacher
            </div>
        </div>
        <div class="panel-body">
            <?php
            if (isset($_POST["assign"])) {
                $teacher = cleanString($_POST["teacher"]);
                $department = cleanString($_POST["department"]);
                $semester = cleanString($_POST["semester"]);
                $course = cleanString($_POST["course"]);

                if (empty($teacher) || empty($department) || empty($semester) || empty($course)) {
                    msgBox("error", "All fields are required");
                } else {
                    $stmt = "INSERT INTO admission VALUES(null,?,?,?,?,?)";
                    $args = array("teacher", $teacher, $department, $semester, $course);
                    try {
                        $db->runQuery($stmt, $args);
                        msgBox("ok", "Teacher Assigned Successfully");
                    } catch (PDOException $e) {
                        msgBox("error", $e->getMessage());
                    }
                }
            }
            ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                <div class="form-group">
                    <label>Teacher</label>
                    <select name="teacher" class="form-control">
                        <?php echo list_teacher(); ?> 
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
                <div class="form-group">
                    <label>Course</label>
                    <select name="course" class="form-control">
                        <?php echo list_courses(); ?>
                    </select>
                </div>
                <input type="submit" name="assign" value="Assign Teacher" class="btn btn-primary" />
            </form>
        </div>
    </div>
    <?php
    }else if($do == "delete"){
        if(isset($_GET["id"])){
            $id = intval($_GET["id"]);
            $db->runQuery("DELETE FROM admission WHERE admit_id = ?",$id);
            msgBox("ok", "Deleted");
            header("refresh:1;location:admit-teachers.php");
        }
    }
} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Assigned Teachers
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Course</th>
                    <th>Action</th>
                </tr>
                <?php
                $db->query("SELECT * FROM admission WHERE admit_type = 'teacher'");
                while ($r = $db->fetchObject()) {
                    $user_name = $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $r->user_id);
                    $department = $db->queryValues("SELECT department_name FROM departments WHERE department_id = ?", "department_name", $r->department_id);
                    $course = $db->queryValues("SELECT course_name FROM courses WHERE course_id = ?", "course_name", $r->course_id);
                    echo "
                    <tr>
                        <td>$user_name->user_name</td>
                        <td>$department->department_name</td>
                        <td>$r->semester_id</td>
                        <td>$course->course_name</td>
                        <td>
                            <div class='btn-group'>
                                <a href='admit-teachers.php?do=edit&amp;id=$r->admit_id' class='btn btn-primary'>Edit</a>
                                <a href='admit-teachers.php?do=delete&amp;id=$r->admit_id' class='btn btn-danger'>Delete</a>
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

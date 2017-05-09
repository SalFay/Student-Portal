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
                    Add Test
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $name = cleanString($_POST["name"]);
                    $body = $_POST["body"];
                    $teacher = cleanString($_POST["teacher"]);
                    $course = cleanString($_POST["course"]);
                    $department = cleanString($_POST["department"]);
                    $semester = cleanString($_POST["semester"]);
                    $date = cleanString($_POST["date"]);
                    $marks = cleanString($_POST["marks"]);
                    if (empty($name) || empty($date)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $date = date("Y-m-d", strtotime($date));
                            $stmt = "INSERT INTO tests VALUES(null,?,?,?,?,?,?,?,?)";
                            $args = array($name, $body,$teacher,$department,$semester,$course,$date,$marks);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Test Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="test.php?action=add">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Details</label>
                        <textarea name="body" class="form-control editor" rows="10"></textarea>
                    </div>
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
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" name="marks" class="form-control" required="" />
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
                    Edit Test
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $name = cleanString($_POST["name"]);
                    $body = $_POST["body"];
                    $teacher = cleanString($_POST["teacher"]);
                    $course = cleanString($_POST["course"]);
                    $department = cleanString($_POST["department"]);
                    $semester = cleanString($_POST["semester"]);
                    $date = cleanString($_POST["date"]);
                    $marks = cleanString($_POST["marks"]);
                    if (empty($name) || empty($date)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $date = date("Y-m-d", strtotime($date));
                            $stmt = "UPDATE tests SET test_name = ?, test_body = ?,"
                                    . "test_teacher = ?, test_department = ?,"
                                    . "test_semester = ?, test_course = ?,"
                                    . "test_date = ?, test_marks = ? WHERE "
                                    . "test_id = ?";
                            $args = array($name, $body,$teacher,$department,$semester,$course,$date,$marks,$id);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Test Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                $db->query("SELECT * FROM tests WHERE test_id = ?",array($id));
                $r = $db->fetchObject();
                ?>
                <form method="post" action="test.php?action=add">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="<?php echo $r->test_name ?>" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Details</label>
                        <textarea name="body" class="form-control editor" rows="10"><?php echo $r->test_body ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Teacher</label>
                        <select name="teacher" class="form-control">
                            <?php echo list_teacher($r->test_teacher); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control">
                            <?php echo list_departments($r->test_department); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control">
                            <?php echo list_semesters($r->test_semester); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Course</label>
                        <select name="course" class="form-control">
                            <?php echo list_courses($r->test_course); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" value="<?php echo $r->test_date ?>" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" name="marks" value="<?php echo $r->test_marks ?>" class="form-control" required="" />
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
                $db->runQuery("DELETE FROM tests WHERE test_id = ?", array($id));
                msgBox("ok", "Test Deleted");
                header("refresh:1;url=test.php");
            } catch (PDOException $e) {
                msgBox("erorr", $e->getMessage());
                header("refresh:3;url=test.php");
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
                Manage Tests
                <div class="pull-right">
                    <a href="test.php?action=add" class="btn btn-sm btn-success">Add Test</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Name</th>
                    <th>Teacher</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Course</th>
                    <th>Marks</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $db->query("SELECT * FROM tests");
                    $v = new Database();
                    while ($r = $db->fetchObject()) {
                        $department = $v->queryValues("SELECT department_name FROM departments WHERE department_id = ?", "department_name",array($r->test_department));
                        $teacher = $v->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name",array($r->test_teacher));
                        $course = $v->queryValues("SELECT course_name FROM courses WHERE course_id = ?", "course_name",array($r->test_course));
                        echo "
                            <tr>
                                <td>$r->test_name</td>
                                <td>$teacher->user_name</td>
                                <td>$department->department_name</td>
                                <td>$r->test_semester</td>
                                <td>$course->course_name</td>
                                <td>$r->test_marks</td>
                                <td>" . date("l d F Y", strtotime($r->test_date)) . "</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='test-result.php?tid=$r->test_id' class='btn btn-success'>View</a>
                                        <a href='test.php?action=edit&amp;id=$r->test_id' class='btn btn-primary'>Edit</a>
                                        <a href='test.php?action=delete&amp;id=$r->test_id' class='btn btn-danger'>Delete</a>
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

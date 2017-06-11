<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["action"])) {
    $a = cleanString($_GET["action"]);
    $department = (int)cleanString($_GET['d']);
    $semester = (int)cleanString($_GET['s']);
    if ($a === "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Course
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST['publish'])) {
                    $name = cleanString($_POST['name']);
                    $body = $_POST['body'];
                    $credits = cleanString($_POST['credit']);
                    $hours = cleanString($_POST['hours']);
                    if (empty($name) || empty($credits) || empty($hours)) {
                        msgBox('error', 'All Fields are required');
                    } else {
                        try {
                            $stmt = 'INSERT INTO courses VALUES(NULL,?,?,?,?,?,?)';
                            $args = [$name, $body, $credits, $hours, $semester, $department];
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Course Added");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="courses.php?action=add">
                    <div class="form-group">
                        <label>Course Department</label>
                        <select name="department" class="form-control" readonly="">
                            <?php echo list_departments($department); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Course Semester</label>
                        <select name="semester" class="form-control" readonly="">
                            <?php echo list_semesters($semester); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Course Name</label>
                        <input type="text" name="name" class="form-control" required=""/>
                    </div>
                    <div class="form-group">
                        <label>Course Body</label>
                        <textarea class="form-control editor" name="body" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Course Credit</label>
                        <input type="number" name="credit" class="form-control" required=""/>
                    </div>
                    <div class="form-group">
                        <label>Course Hours</label>
                        <input type="number" name="hours" class="form-control" required=""/>
                    </div>
                    <input type="submit" name="publish" value="Add Course" class="btn btn-primary"/>
                </form>
            </div>
        </div>
        <?php
    } else {
        if ($a === "edit") {
            if (isset($_GET["id"])) {
                $id = (int)cleanString($_GET["id"]);
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Update Course
                            <div class="pull-right">
                                <a href="courses.php" class="btn btn-sm btn-success">Back</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (isset($_POST["publish"])) {
                            $name = cleanString($_POST["name"]);
                            $body = $_POST["body"];
                            $credits = cleanString($_POST["credit"]);
                            $hours = cleanString($_POST["hours"]);
                            if (empty($name) || empty($credits) || empty($hours)) {
                                msgBox("error", "All Fields are required");
                            } else {
                                try {
                                    $stmt = "UPDATE courses SET course_name = ?,"
                                        . "course_body = ?, course_credit = ?,"
                                        . "course_hours = ?, course_semester = ?"
                                        . ", course_department = ? WHERE"
                                        . " course_id = ?";
                                    $args = [$name, $body, $credits, $hours, $semester, $department, $id];
                                    $db->runQuery($stmt, $args);
                                    msgBox("ok", "Course Updated");
                                } catch (PDOException $e) {
                                    msgBox("error", $e->getMessage());
                                }
                            }
                        }
                        $db = new Database();
                        $db->query("SELECT * FROM courses WHERE course_id = ?", [$id]);
                        $r = $db->fetchObject();
                        ?>
                        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
                            <div class="form-group">
                                <label>Course Department</label>
                                <select name="department" class="form-control" readonly="">
                                    <?php echo list_departments($r->course_department); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Course Semester</label>
                                <select name="semester" class="form-control" readonly="">
                                    <?php echo list_semesters($r->course_semester); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Course Name</label>
                                <input type="text" name="name" value='<?php echo $r->course_name ?>'
                                       class="form-control" required=""/>
                            </div>
                            <div class="form-group">
                                <label>Course Body</label>
                                <textarea class="form-control editor" name="body"
                                          rows="10"><?php echo $r->course_body ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Course Credit</label>
                                <input type="number" name="credit" value='<?php echo $r->course_credit ?>'
                                       class="form-control" required=""/>
                            </div>
                            <div class="form-group">
                                <label>Course Hours</label>
                                <input type="number" name="hours" value='<?php echo $r->course_hours ?>'
                                       class="form-control" required=""/>
                            </div>

                            <input type="submit" name="publish" value="Update Course" class="btn btn-primary"/>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            if ($a === "delete") {
                if (isset($_GET["id"])) {
                    $id = intval(cleanString($_GET["id"]));
                    try {
                        $db->runQuery("DELETE FROM courses WHERE course_id = ?", [$id]);
                        msgBox("ok", "Course Deleted");
                        header("refresh:1;url=courses.php");
                    } catch (PDOException $e) {
                        msgBox("erorr", $e->getMessage());
                        header("refresh:3;url=courses.php");
                    }
                }
            } else {
                echo "Nothing to do";
            }
        }
    }
} else {
    $department = (int)cleanString($_GET['d']);
    $semester = (int)cleanString($_GET['s']);
    $params = '&amp;d='.$department.'&amp;s='.$semester;
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Manage Courses
                <div class="pull-right">
                    <a href="courses.php?action=add<?php echo $params ?>"
                       class="btn btn-sm btn-success">Add Course</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Department</th>
                    <th>Name</th>
                    <th>Credits</th>
                    <th>Hours</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $stmt = '
                        SELECT courses.*, d.department_name FROM courses 
                        LEFT JOIN departments d 
                        ON d.department_id = courses.course_department 
                        WHERE courses.course_department = ? AND course_semester = ?
                    ';
                    $db->query($stmt, [$department, $semester]);
                    while ($r = $db->fetchObject()) {
                        echo "
                            <tr>
                                <td>$r->department_name</td>
                                <td>$r->course_name</td>
                                <td>$r->course_credit</td>
                                <td>$r->course_hours</td>
                                <td>$r->course_semester</td>
                                
                                <td>
                                    <div class='btn-group btn-group-sm'>
                                        <a href='test.php?c=$r->course_id&amp;$params' class='btn btn-success'>
                                            Manage test
                                        </a>
                                        <a href='courses.php?action=edit&amp;id=$r->course_id.$params' class='btn btn-primary'>Edit</a>
                                        <a href='courses.php?action=delete&amp;id=$r->course_id' onclick='return cdel()' class='btn btn-danger'>Delete</a>
                                    </div>
                                </td>
                            </tr>
                        ";
                    }
                } catch (PDOException $e) {
                    msgBox('error', $e->getMessage());
                }
                ?>
            </table>
        </div>
    </div>
    <?php
}
?>

<?php require 'footer.php' ?>

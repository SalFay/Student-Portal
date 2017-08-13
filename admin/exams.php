<?php require 'header.php' ?>
<?php
$db = new Database();
$department = (int) $_GET['d'];
$semester = (int) $_GET['s'];
$params = 'd='.$department.'&amp;s='.$semester;
if (isset($_GET["action"])) {
    $a = cleanString($_GET["action"]);
    if ($a === 'add') {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Exam
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $name = cleanString($_POST["name"]);
                    $type = cleanString($_POST["type"]);
                    $date = cleanString($_POST["date"]);
                    if (empty($name) || empty($date)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $date = date("Y-m-d", strtotime($date));
                            $stmt = "INSERT INTO exams VALUES(null,?,?,?,?,?)";
                            $args = array($name, $type, $department, $semester, $date);
                            $db->runQuery($stmt, $args);
                            $exam_id = $db->lastInsertId();
                            // Get Student
                            $db->query("SELECT * FROM admission WHERE admit_type = 'student' AND department_id = ? AND semester_id = ?", array($department, $semester));
                            $rdb = new Database();
                            while ($r = $db->fetchObject()) {
                                $student_id = $r->user_id;
                                $courses = getCourses($department, $semester);
                                foreach ($courses as $course) {
                                    $stmt = "INSERT INTO exam_result VALUES(null,?,?,?,?,?)";
                                    $args = [$exam_id, $student_id, $course, 0, 0];
                                    $rdb->runQuery($stmt, $args);
                                    $credit_hours = $db->queryValues("SELECT SUM(course_hours) as ch FROM courses WHERE course_department = ? AND course_semester = ?", "ch", array($department, $semester))->ch;
                                }
                                $rdb->runQuery("INSERT INTO gpa VALUES(null,?,?,0,?)", array($student_id, $exam_id, $credit_hours));
                            }
                            msgBox("ok", "Exam Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="exams.php?action=add&amp;<?php echo $params ?>">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="mid">Mid Term</option>
                            <option value="final">Final Term</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control" disabled>
                            <?php echo list_departments($department); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control" disabled>
                            <?php echo list_semesters($semester); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required="" />
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
                        Edit Exam
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST["publish"])) {
                        $name = cleanString($_POST["name"]);
                        $type = cleanString($_POST["type"]);
                        $date = cleanString($_POST["date"]);
                        if (empty($name) || empty($date)) {
                            msgBox("error", "Please provide all fields");
                        } else {
                            try {
                                $date = date("Y-m-d", strtotime($date));
                                $stmt = "UPDATE exams SET exam_name = ?, exam_type = ?,"
                                        . "exam_department = ?,exam_semester = ?,"
                                        . "exam_date = ? WHERE exam_id = ?";
                                $args = array($name, $type, $department, $semester, $date, $id);
                                $db->runQuery($stmt, $args);
                                msgBox("ok", "Exam Updated");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                        }
                    }
                    $db->query("SELECT * FROM exams WHERE exam_id = ?", array($id));
                    $r = $db->fetchObject();
                    ?>
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="<?php echo $r->exam_name ?>" class="form-control" required="" />
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <?php
                                $mid = ($r->exam_type === "mid") ? "selected=''" : "";
                                $final = ($r->exam_type === "final") ? "selected=''" : "";
                                ?>
                                <option value="mid" <?php echo $mid ?>>Mid Term</option>
                                <option value="final" <?php echo $final ?>>Final Term</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-control" disabled>
                                <?php echo list_departments($r->exam_department); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <select name="semester" class="form-control" disabled>
                                <?php echo list_semesters($r->exam_semester); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" value="<?php echo $r->exam_date ?>" class="form-control" required="" />
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
                $db->runQuery("DELETE FROM exams WHERE exam_id = ?", array($id));
                msgBox("ok", "Exam Deleted");
                header("refresh:1;url=exams.php");
            } catch (PDOException $e) {
                msgBox("erorr", $e->getMessage());
                header("refresh:3;url=exams.php?".$params);
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
                Manage Exams
                <div class="pull-right">
                    <a href="exams.php?action=add&amp;<?php echo $params ?>" class="btn btn-sm btn-success">Add Exam</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $stmt = '
                        SELECT e.*, d.department_name FROM exams e
                        LEFT JOIN departments d 
                        ON e.exam_department = d.department_id
                        WHERE d.department_id = ?
                    ';
                    $db->query($stmt,[$department]);
                    $cdb = new Database();
                    while ($r = $db->fetchObject()) {
                        echo "
                            <tr>
                                <td>$r->exam_name</td>
                                <td>$r->exam_type</td>
                                <td>$r->department_name</td>
                                <td>$r->exam_semester</td>
                                <td>" . date('l d F Y', strtotime($r->exam_date)) . "</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='exam-result.php?exam_id=$r->exam_id&amp;$params' class='btn btn-success'>View</a>
                                        <a href='exams.php?action=edit&amp;id=$r->exam_id&amp;$params' class='btn btn-primary'>Edit</a>
                                        <a href='exams.php?action=delete&amp;id=$r->exam_id&amp;$params' class='btn btn-danger'>Delete</a>
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

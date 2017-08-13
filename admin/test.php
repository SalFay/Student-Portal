<?php require __DIR__.'/header.php' ?>
<?php
$db = new Database();
$department = (int) $_GET['d'];
$semester = (int) $_GET['s'];
$course = (int) $_GET['c'];
$params = 'd='.$department.'&amp;s='.$semester.'&amp;c='.$course;
if (isset($_GET["action"])) {
    $a = cleanString($_GET["action"]);
    if ($a === "add") {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Test
                    <div class="btn-group btn-group-sm pull-right">
                        <a href="test.php?<?php echo $params ?>" class="btn btn-success">All Tests</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $name = cleanString($_POST["name"]);
                    $body = $_POST["body"];
                    $teacher = $_SESSION['user_id'];
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
                <form method="post" action="test.php?action=add&amp;<?php echo $params ?>">
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
                        <select name="teacher" class="form-control" disabled>
                            <?php echo list_teacher($_SESSION['user_id']); ?>
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
                        <label>Course</label>
                        <select name="course" class="form-control" disabled>
                            <?php echo list_courses($course); ?>
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
            $id = (int)cleanString($_GET["id"]);
            ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Edit Test
                    <div class="btn-group btn-group-sm pull-right">
                        <a href="test.php?<?php echo $params ?>" class="btn btn-success">
                            All Tests
                        </a>
                        <a href="test.php?action=add&amp;<?php echo $params ?>" class="btn btn-primary">
                            Add Test
                        </a>
                        <div class="clearfix"></div>
                    </div>
                </div>

            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $name = cleanString($_POST["name"]);
                    $body = $_POST["body"];
                    $teacher = $_SESSION['user_id'];
                    $date = cleanString($_POST["date"]);
                    $marks = cleanString($_POST["marks"]);
                    if (empty($name) || empty($date)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $date = date("Y-m-d", strtotime($date));
                            $stmt = "UPDATE tests SET test_name = ?, test_body = ?,"
                                    . "test_teacher = ?, test_department = ?,"
                                    . 'test_semester = ?, test_course = ?,'
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
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
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
                        <select name="teacher" class="form-control" disabled>
                            <?php echo list_teacher($r->test_teacher); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control" disabled>
                            <?php echo list_departments($r->test_department); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control" disabled>
                            <?php echo list_semesters($r->test_semester); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Course</label>
                        <select name="course" class="form-control" disabled>
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
            $id = (int)cleanString($_GET["id"]);
            try {
                $db->runQuery('DELETE FROM tests WHERE test_id = ?', array($id));
                msgBox("ok", "Test Deleted");
                header('refresh:1;url=test.php');
            } catch (PDOException $e) {
                msgBox('error', $e->getMessage());
                header('refresh:3;url=test.php');
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
                    <a href="test.php?action=add&amp;<?php echo $params ?>" class="btn btn-sm btn-success">Add Test</a>
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
                    $stmt = '
                        SELECT t.*, d.department_name,c.course_name,i.user_name 
                        FROM tests t
                        LEFT JOIN departments d
                        ON d.department_id = t.test_department
                        LEFT JOIN courses c
                        ON c.course_id = t.test_course
                        LEFT JOIN users i
                        ON i.user_id = t.test_teacher
                        WHERE t.test_department = ?
                        AND t.test_semester = ?
                        AND t.test_course = ?
                        AND t.test_teacher = ?
                    ';

                    $db->query($stmt,[$department,$semester,$course,$_SESSION['user_id']]);
                    $v = new Database();
                    while ($r = $db->fetchObject()) {
                        echo "
                            <tr>
                                <td>$r->test_name</td>
                                <td>$r->user_name</td>
                                <td>$r->department_name</td>
                                <td>$r->test_semester</td>
                                <td>$r->course_name</td>
                                <td>$r->test_marks</td>
                                <td>" . date("l d F Y", strtotime($r->test_date)) . "</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='test-result.php?tid=$r->test_id&amp;$params' class='btn btn-success'>View</a>
                                        <a href='test.php?action=edit&amp;id=$r->test_id&amp;$params' class='btn btn-primary'>Edit</a>
                                        <a href='test.php?action=delete&amp;id=$r->test_id&amp;$params' class='btn btn-danger'>Delete</a>
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

<?php require __DIR__.'/footer.php' ?>

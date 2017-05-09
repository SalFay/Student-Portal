<?php require 'header.php' ?>
<?php
$db = new Database();
if (isset($_GET["tid"])) {
    $test_id = intval($_GET["tid"]);
    if (isset($_GET["do"])) {
        $do = cleanString($_GET["do"]);
        if ($do == "add") {
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        Add Test Result
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    if (isset($_POST['addresult'])) {
                        $result = array_map("cleanstring", $_POST);
                        unset($result['addresult']);
                        $flag = TRUE;
                        foreach ($result as $v) {
                            $v = trim($v);
                            if (empty($v) || $v == 0 || !ctype_digit($v)) {
                                $flag = FALSE;
                            }
                        }
                        if ($flag) {
                            $success = TRUE;
                            foreach ($result as $k => $v) {
                                list($x, $id) = explode("_", $k);
                                $stmt = "INSERT INTO test_result VALUES(null,?,?,?)";
                                $args = array($test_id, $id, $v);
                                try {
                                    $db->runQuery($stmt, $args);
                                } catch (PDOException $e) {
                                    msgBox("error", $e->getMessage());
                                    $success = FALSE;
                                }
                            }
                            msgBox("ok", "Result Added SUcessfullt");
                        } else {
                            msgBox("error", "Invalid Entry. Please review your form");
                        }
                    }
                    ?>
                    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                        <div class="form-group">
                            <label class="control-label col-sm-6"><strong>Student Name</strong></label>
                            <label class="control-label col-sm-6"><strong>Obtained Marks</strong></label>
                        </div>
                        <?php
                        $db->query("SELECT * FROM tests WHERE test_id = ?", array($test_id));
                        $result = $db->fetchObject();
                        $department = $result->test_department;
                        $semester = $result->test_semester;
                        $course = $result->test_course;
                        $db->query("SELECT user_id FROM admission WHERE admit_type = 'student' AND department_id = ? AND semester_id = ?", array($department, $semester));
                        while ($r = $db->fetchObject()) {
                            $student_name = $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $r->user_id);
                            ?>
                            <div class="form-group">
                                <label class="control-label col-sm-6"><?php echo $student_name->user_name ?></label>
                                <div class="col-sm-6">
                                    <input type="number" name="marks_<?php echo $r->user_id ?>" value="0" class="form-control" /> 
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <input type="submit" name="addresult" value="Add Result" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <?php
        } else if ($do == "edit") {
            if (isset($_GET["tid"])) {
                $id = (int)$_GET['tid'];
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Edit Test Result
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (isset($_POST['addresult'])) {
                            $result = array_map("cleanstring", $_POST);
                            unset($result['addresult']);
                            $flag = TRUE;
                            //print_r($result);
                            foreach ($result as $v) {
                                $v = trim($v);
                                if (empty($v) || $v == 0 || !ctype_digit($v)) {
                                    $flag = FALSE;
                                }
                            }
                            if ($flag) {
                                $success = TRUE;
                                foreach ($result as $k => $v) {
                                    list($x, $id) = explode("_", $k);
                                    $stmt = "UPDATE test_result SET student_marks = ?"
                                            . " WHERE student_id = ? AND test_id = ?";
                                    $args = array($v,$id,$test_id);
                                    try {
                                        $db->runQuery($stmt, $args);
                                    } catch (PDOException $e) {
                                        msgBox("error", $e->getMessage());
                                        $success = FALSE;
                                    }
                                }
                                msgBox("ok", "Result Updated Successfully");
                            } else {
                                msgBox("error", "Invalid Entry. Please review your form");
                            }
                        }
                        ?>
                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-6"><strong>Student Name</strong></label>
                                <label class="control-label col-sm-6"><strong>Obtained Marks</strong></label>
                            </div>
                            <?php
                            $db->query("SELECT * FROM tests WHERE test_id = ?", array($test_id));
                            $result = $db->fetchObject();
                            $department = $result->test_department;
                            $semester = $result->test_semester;
                            $course = $result->test_course;
                            $db->query("SELECT user_id FROM admission WHERE admit_type = 'student' AND department_id = ? AND semester_id = ?", array($department, $semester));
                            while ($r = $db->fetchObject()) {
                                $student_name = $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $r->user_id);
                                $marks = $db->queryValues("SELECT * FROM test_result WHERE test_id = ? AND student_id = ?", "student_marks", [$id,$r->user_id]);
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-6"><?php echo $student_name->user_name ?></label>
                                    <div class="col-sm-6">
                                        <input type="number" name="marks_<?php echo $r->user_id ?>" value="<?php echo $marks->student_marks ?>" class="form-control" /> 
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <input type="submit" name="addresult" value="Add Result" class="btn btn-primary" />
                        </form>
                    </div>
                </div>
                <?php
            }
        }
    } else {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Test Result
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Student</th>
                        <th>Obtained Marks</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    try {
                        $db->query("SELECT * FROM test_result WHERE test_id = ?", $test_id);
                        if ($db->rowCount() > 0) {
                            while ($r = $db->fetchObject()) {
                                $student = $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $r->student_id)->user_name;
                                echo "
                                <tr>
                                    <td>$student</td>
                                    <td>$r->student_marks</td>
                                    <td>
                                        <a href='test-result.php?tid=$test_id&amp;do=edit' class='btn btn-primary'>Edit</a>
                                    </td>
                                </tr>
                            ";
                            }
                        } else {
                            echo "<p>No result has been declared yet. Click the button below to add the result</p>"
                            . "<a href='test-result.php?tid=$test_id&amp;do=add' class='btn btn-primary btn-block'>Add Result</a>";
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
} else {
    header("location:test.php");
}
?>
<?php
require 'footer.php';

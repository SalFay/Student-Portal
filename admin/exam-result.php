<?php
require 'header.php';
$db = new Database();
if (isset($_GET["exam_id"])) {
    $exam_id = intval($_GET["exam_id"]);
    if (isset($_GET["do"])) {
        $do = cleanString($_GET["do"]);
        if ($do == "edit") {
            if (isset($_GET["id"])) {
                $id = intval($_GET["id"]);
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">Manage Result</div>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (isset($_POST["update"])) {
                            try {
                                $obtained = array_map("intval", $_POST["obtained"]);
                                $total = array_map("intval", $_POST["total"]);
                                $courses = $_POST["course"];
                                $total_marks = 0;
                                $obtained_marks = 0;
                                for ($i = 0; $i < count($courses); $i++) {
                                    $total_marks += $total[$i];
                                    $obtained_marks += $obtained[$i];
                                    $stmt = "UPDATE exam_result SET result_marks = ?,"
                                            . "result_total = ? WHERE exam_id = ? AND "
                                            . "student_id = ? AND course_id = ?";
                                    $args = [$obtained[$i], $total[$i], $exam_id, $id, $courses[$i]];
                                    $db->runQuery($stmt, $args);
                                }
                                $percentage = ($obtained_marks/$total_marks)*100;
                                $gpa = getGpa($percentage);
                                $db->runQuery("UPDATE gpa SET gpa_marks = ? WHERE student_id = ? AND exam_id = ?",array($gpa,$id,$exam_id,));
                                
                                // Generate CGPA if this is Final Term
                                $exam_type = $db->queryValues("SELECT exam_type FROM exams WHERE exam_id = ?", "exam_type", $exam_id)->exam_type;
                                if($exam_type=="final"){
                                    $total_gpas = $db->queryValues("SELECT SUM(gpa_marks) as tgpa FROM gpa WHERE exam_id = ? AND student_id = ?", "tgpa", array($exam_id,$id))->tgpa;
                                    $total_ch = $db->queryValues("SELECT SUM(gpa_credit_hours) as tch FROM gpa WHERE exam_id = ? AND student_id = ?", "tch", array($exam_id,$id))->tch;
                                    $cgpa_marks = $total_gpas/$total_ch;
                                    // Update CGPA
                                    $db->query("SELECT * FROM cgpa WHERE student_id = ? AND exam_id = ?",array($id,$exam_id));
                                    if($db->rowCount()>0){
                                        $r = $db->fetchObject()->cgpa_id;
                                        $db->runQuery("UPDATE cgpa SET cgpa_marks = ? WHERE cgpa_id = ?",array($cgpa_marks,$r));
                                    }else{
                                        $db->runQuery("INSERT INTO cgpa VALUES(null,?,?,?)", array($id,$cgpa_marks,$exam_id));
                                    }
                                }
                                msgBox("ok", "Result updated");
                            } catch (PDOException $e) {
                                msgBox("error", $e->getMessage());
                            }
                            
                            
                        }
                        ?>
                        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                            <table class="table">
                                <tr>
                                    <th></th>
                                    <th>Obtained Marks</th>
                                    <th>Total Marks</th>
                                </tr>
                                <?php
                                // Get GPA
                                $gpa = $db->queryValues("SELECT gpa_marks FROM gpa WHERE exam_id = ? AND student_id = ?", "gpa_marks", array($exam_id,$id))->gpa_marks;
                                $db->query("SELECT * FROM exam_result WHERE exam_id = ? AND student_id = ?", array($exam_id, $id));
                                while ($r = $db->fetchObject()) {
                                    $course = $db->queryValues("SELECT course_name FROM courses WHERE course_id = ?", "course_name", $r->course_id)->course_name;
                                    echo "
                        <tr>
                            <td>$course</td>
                            <td><input type='text' name='obtained[]' value='$r->result_marks' class='form-control' /></td>
                            <td><input type='text' name='total[]' value='$r->result_total' class='form-control' />
                                <input type='hidden' name='course[]' value='$r->course_id' /></td>
                        </tr>
                    ";
                                }
                                ?>
                            </table>
                            <input type="submit" name="update" class="btn btn-primary" value="Update" />
                        </form>
                    </div>
                </div>
                <?php
            } else {
                header("location:exam-result.php?exam_id=$exam_id");
                exit();
            }
        } else {
            header("location:exam-result.php?exam_id=$exam_id");
            exit();
        }
    } else {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Manage Exam (<?php echo $db->queryValues("SELECT exam_name FROM exams WHERE exam_id = ?", "exam_name", $exam_id)->exam_name; ?>)
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <tr>
                        <td>Student</td>
                        <td>Total Marks</td>
                        <td>Obtained Marks</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    $db->query("SELECT DISTINCT(student_id) FROM exam_result WHERE exam_id = ?", array($exam_id));
                    while ($r = $db->fetchObject()) {
                        $student_name = $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $r->student_id)->user_name;
                        $obtained = $db->queryValues("SELECT SUM(result_marks) as obtained FROM exam_result WHERE exam_id = ? AND student_id = ?", "obtained", array($exam_id, $r->student_id))->obtained;
                        $total = $db->queryValues("SELECT SUM(result_total) as total FROM exam_result WHERE exam_id = ? AND student_id = ?", "total", array($exam_id, $r->student_id))->total;
                        echo "
                        <tr>
                            <td>$student_name</td>
                            <td>$total</td>
                            <td>$obtained</td>
                            <td>
                                <a href='exam-result.php?exam_id=$exam_id&amp;do=edit&amp;id=$r->student_id' class='btn btn-success' >Manage Result</a>
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
} else {
    header("location:exams.php");
    exit();
}
require 'footer.php';

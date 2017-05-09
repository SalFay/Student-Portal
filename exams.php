<?php
require 'header.php';
$db = new Database();
$db->query("SELECT * FROM admission WHERE user_id = ?", $_SESSION["std_id"]);
$r = $db->fetchObject();
$department = $r->department_id;
$semester = $r->semester_id;
if (empty($_SESSION["std_id"])) {
    msgBox("error", "You are not logged-in");
} else {
    ?>
    <!-- Page Title -->
    <div class="section section-breadcrumbs hidden-print">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>
                        <?php echo $_SESSION["std_name"] ?>'s Dashboard
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <?php
                    if (isset($_GET["id"])) {
                        $flag = FALSE;
                        $id = intval($_GET["id"]);
                        $total_marks = 0;
                        $total_obtained = 0;
                        $db->query("SELECT * FROM exam_result WHERE exam_id = ? AND student_id = ?", array($id, $_SESSION["std_id"]));
                        if ($db->rowCount() > 0) {
                            $flag = TRUE;
                        }
                        ?>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Subject</th>
                                <th>Total</th>
                                <th>Obtained</th>
                            </tr>
                            <?php
                            while ($r = $db->fetchObject()) {
                                $total_marks += $r->result_total;
                                $total_obtained += $r->result_marks;
                                $subject = $db->queryValues("SELECT course_name FROM courses WHERE course_id = ?", "course_name", $r->course_id)->course_name;
                                echo "
                                    <tr>
                                        <td>$subject</td>
                                        <td>$r->result_total</td>
                                        <td>$r->result_marks</td>
                                    </tr>
                                ";
                            }
                            ?>
                        </table>
                        <div class="row">
                            <div class="col-xs-6">
                                Total marks: <?php echo $total_marks ?>
                            </div>
                            <div class="col-xs-6">
                                Total Obtained: <?php echo $total_obtained ?>
                            </div>
                        </div>
                        <?php
                        if ($flag) {
                            $gpa = $db->queryValues("SELECT gpa_marks FROM gpa WHERE exam_id = ? AND student_id = ?", "gpa_marks", [$id, $_SESSION["std_id"]])->gpa_marks;
                            $cgpa = $db->queryValues("SELECT cgpa_marks FROM cgpa WHERE exam_id = ? AND student_id = ?", "cgpa_marks", [$id, $_SESSION["std_id"]])->cgpa_marks;
                            ?>
                            <div class="row">
                                <div class="col-xs-6">
                                    GPA: <?php echo $gpa ?>
                                </div>
                                <div class="col-xs-6">
                                    CGPA: <?php echo (empty($cgpa))?"":$cgpa; ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="blog-post blog-single-post">
                            <div class="single-post-title">
                                <h3>Exams</h3>
                            </div>
                            <div class="single-post-content">
                                <table class="table table-striped">
                                    <?php
                                    $db->query("SELECT * FROM exams WHERE exam_department = ?", [$department]);
                                    while ($r = $db->fetchObject()) {
                                        echo "
                                            <tr>
                                                <td><a href='exams.php?id=$r->exam_id'>$r->exam_name</a></td>
                                                <td>" . ucfirst($r->exam_type) . "</td>
                                                <td>" . date("l D F Y", strtotime($r->exam_date)) . "</td>
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
                    <?php print_button(); ?>
                </div>
                <?php require 'sidebar-user.php'; ?>
            </div>
        </div>
    </div>
    <?php
}
require 'footer.php';

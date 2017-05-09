<?php
require 'header.php';
$db = new Database();
if (empty($_SESSION["std_id"])) {
    msgBox("error", "You are not logged-in");
} else {
    $user_id = $_SESSION["std_id"];
    $db->query("SELECT * FROM test_result WHERE student_id = ?", $_SESSION["std_id"]);
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
                    <h3>Your Tests</h3>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Total Marks</th>
                            <th>Obtained Marks</th>
                        </tr>
                        <?php
                        $db2 = new Database();
                        while($r = $db->fetchObject()){
                            $db2->query("SELECT * from tests WHERE test_id = ?",$r->test_id);
                            $t  = $db2->fetchObject();
                            $course= $db->queryValues("SELECT * FROM courses WHERE course_id = ?", "course_name", $t->test_course)->course_name;
                            echo "
                                <tr>
                                    <td>$t->test_name</td>
                                    <td>$course</td>
                                    <td>$t->test_marks</td>
                                    <td>$r->student_marks</td>
                                </tr>
                            ";
                        }
                        ?>
                    </table>
                </div>
                <?php require 'sidebar-user.php'; ?>
            </div>
        </div>
    </div>
    <?php
}
require 'footer.php';

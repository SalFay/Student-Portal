<?php
require 'header.php';
$db = new Database();
if (empty($_SESSION["std_id"])) {
    msgBox("error", "You are not logged-in");
} else {
    $user_id = intval($_SESSION["std_id"]);
    $db->query("SELECT * FROM admission WHERE user_id = ?", $_SESSION["std_id"]);
    $r = $db->fetchObject();
    $department = $r->department_id;
    $semester = $r->semester_id;
    $courses = implode(",", getCourses($department, $semester));
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
                        $id = intval($_GET["id"]);
                        $db->query("SELECT * FROM assignment_files WHERE af_id = ?", $id);
                        $r = $db->fetchObject();
                        $db2 = new Database();
                        $db2->query("SELECT * FROM assignments WHERE asgn_id = ?", $r->asgn_id);
                        $x = $db2->fetchObject();
                        ?>
                        <h3>Assignment: <?php echo $x->asgn_title ?></h3>
                        <?php
                        if (isset($_POST["upload"])) {

                            if (empty($_FILES["afile"]["name"])) {
                                msgBox("error", "Please select a file");
                            } else {
                                if (move_uploaded_file($_FILES["afile"]["tmp_name"], "content/files/" . $_FILES["afile"]["name"])) {
                                    $dbx = new Database();
                                    $stmt = "UPDATE assignment_files SET af_file = ? WHERE af_id = ?";
                                    $dbx->runQuery($stmt, [$_FILES["afile"]["name"], $id]);
                                    msgBox("ok", "Assignment file submitted successfully");
                                } else {
                                    msgBox("error", "Error Occured");
                                }
                            }
                        }
                        ?>
                        <p><?php echo $x->asgn_body ?></p>
                        <table class="table table-striped">
                            <tr>
                                <td><strong>Assigned By:</strong></td>
                                <td><?php echo $db->queryValues("SELECT user_name FROM users WHERE user_id = ?", "user_name", $x->teacher_id)->user_name ?></td>
                            </tr>
                            <tr>
                                <td><strong>Expiry:</strong></td>
                                <td><?php echo date("l d F Y", strtotime($x->asgn_expiry)); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><?php echo (empty($r->af_file)) ? "Not Submitted" : "Submitted"; ?></td>
                            </tr>
                            <?php
                            if (!empty($r->af_file)) {
                                ?>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><a href="content/files/<?php echo $r->af_file ?>"><?php echo $r->af_file ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td><strong>Comments:</strong></td>
                                <td><?php echo $r->af_comments; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Marks:</strong></td>
                                <td><?php echo $r->af_marks; ?></td>
                            </tr>
                        </table>
                        <h4>Submit Assignment File</h4>

                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" name="afile" class="form-group" />
                            </div>
                            <input type="submit" name="upload" value="Upload" class="btn btn-primary" />
                        </form>
                        <?php
                    } else {
                        ?>
                        <h3>Your Assignments</h3>
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <th>Name</th>
                                <th>Expiry</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $db->query("SELECT * FROM assignments WHERE asgn_status = 1 AND course_id IN(?) ORDER BY asgn_id DESC", $courses);
                            $db2 = new Database();
                            while ($r = $db->fetchObject()) {
                                $db2->query("SELECT * FROM assignment_files WHERE asgn_id = ? AND student_id = ?", [$r->asgn_id, $user_id]);
                                if ($db2->rowCount() > 0) {
                                    $x = $db2->fetchObject();
                                    $status = (empty($x->af_file)) ? "Not Submitted" : "Submitted";
                                    echo "
                                    <tr>
                                        <td>$r->asgn_title</td>
                                        <td>" . date("l D F Y", strtotime($r->asgn_expiry)) . "</td>
                                        <td>$status</td>
                                        <td><a href='assignments.php?id=$x->af_id' class='btn btn-primary'>View</a></td>
                                    </tr>
                                ";
                                }
                            }
                            ?>
                        </table>
                        <?php
                    }
                    ?>
                </div>
                <?php require 'sidebar-user.php'; ?>
            </div>
        </div>
    </div>
    <?php
}
require 'footer.php';

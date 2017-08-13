<?php
require __DIR__ . '/header.php';
$db = new Database();
$user_id = (int)$_SESSION["user_id"];
$course = (int)$_GET['c'];
if (isset($_GET['action'])) {
    $a = cleanString($_GET['action']);
    if ($a === 'add') {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Add Assignment
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST['publish'])) {
                    $title = cleanString($_POST['title']);
                    $body = cleanString($_POST['body']);
                    $expiry = cleanString($_POST['expiry']);
                    $status = $_POST['status'];
                    if (empty($title) || empty($body) || empty($expiry)) {
                        msgBox('error', 'All fields are required');
                    } else {
                        try {
                            $date_expiry = date('Y-m-d', strtotime($expiry));
                            $stmt = 'INSERT INTO assignments VALUES(NULL,?,?,?,?,CURDATE(),?,?)';
                            $args = [$_SESSION['user_id'], $course, $title, $body, $expiry, $status];
                            $db->runQuery($stmt, $args);
                            $assignment_id = $db->lastInsertId();
                            $students = [];
                            $req = $db->queryValues('SELECT course_department, course_semester FROM courses WHERE course_id = ?',
                                ['course_department', 'course_semester'], $course);
                            $db->query('SELECT * FROM admission WHERE department_id = ? AND semester_id = ?',
                                [$req->course_department, $req->course_semester]);
                            $db2 = new Database();
                            while ($r = $db->fetchObject()) {
                                $stmt = 'INSERT INTO assignment_files VALUES(NULL,?,?,?,?,?)';
                                $args = [$assignment_id, $r->user_id, '', '', 0];
                                $db2->runQuery($stmt, $args);
                            }
                            msgBox("ok", "Assignment Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required=""/>
                    </div>
                    <div class="form-group">
                        <label>Details</label>
                        <textarea class="form-control" name="body" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry" class="form-control" required=""/>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <?php echo assignment_status(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Course</label>
                        <select class="form-control" name="course" disabled>
                            <?php echo list_courses($course); ?>
                        </select>
                    </div>
                    <input type="submit" name="publish" value="Publish Assignment" class="btn btn-primary"/>
                </form>
            </div>
        </div>
        <?php
    } else {
        if ($a == "view") {
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Manage Submitted Assignment
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-striped">
                            <tr>
                                <th>Student</th>
                                <th>File</th>
                                <th>Comments</th>
                                <th>Marks</th>
                                <th>Edit</th>
                            </tr>
                            <?php
                            $db->query('SELECT * FROM assignment_files WHERE asgn_id = ?', $id);
                            while ($r = $db->fetchObject()) {
                                $student = $db->queryValues("SELECT * FROM users WHERE user_id = ?", "user_name",
                                    $r->student_id);
                                $file = empty($r->af_file) ? "Not Submitted" : "<a href='../content/files/$r->af_file'>$r->af_file</a>";
                                ?>
                                <tr>
                                    <td><?php echo $student->user_name ?></td>
                                    <td><?php echo $file ?></td>
                                    <td><?php echo $r->af_comments ?></td>
                                    <td><?php echo $r->af_marks ?></td>
                                    <td>
                                        <a href='assignments.php?do=edit-file&amp;id=<?php echo $r->af_id ?>'
                                           class='btn btn-primary'>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <?php
            }
        } else {
            if ($a == "edit-file") {
                if (isset($_GET["id"])) {
                    $id = intval($_GET["id"]);
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">
                                Edit submitted Assignment
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (isset($_POST["update"])) {
                                $comments = cleanString($_POST["comments"]);
                                $marks = intval($_POST["marks"]);
                                $db->runQuery("UPDATE assignment_files SET af_comments = ?,"
                                    . "af_marks = ? WHERE af_id = ?", [$comments, $marks, $id]);
                                msgBox("ok", "Updated");
                            }
                            $db->query("SELECT * FROM assignment_files WHERE af_id = ?", $id);
                            $r = $db->fetchObject();
                            ?>
                            <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea class="form-control"
                                              name="comments"><?php echo $r->af_comments ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Marks</label>
                                    <input type="text" name="marks" value="<?php echo $r->af_marks ?>"
                                           class="form-control"/>
                                </div>
                                <input type="submit" name="update" class="btn btn-primary" value="Update"/>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            } else {
                if ($a == "edit") {
                    if (isset($_GET["id"])) {
                        $id = (int)cleanString($_GET["id"]);
                        ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Edit Assignment
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php
                                if (isset($_POST["publish"])) {
                                    $title = cleanString($_POST["title"]);
                                    $body = cleanString($_POST["body"]);
                                    $expiry = cleanString($_POST["expiry"]);
                                    $status = $_POST["status"];
                                    if (empty($title) || empty($body) || empty($expiry)) {
                                        msgBox("error", "All fields are required");
                                    } else {
                                        try {
                                            $date_expiry = date("Y-m-d", strtotime($expiry));
                                            $stmt = "UPDATE assignments SET asgn_title = ?,"
                                                . "asgn_body = ?, asgn_expiry = ?,"
                                                . "asgn_status = ?, course_id = ? "
                                                . "WHERE asgn_id = ?";
                                            $args = [$title, $body, $date_expiry, $status, $course, $id];
                                            $db->runQuery($stmt, $args);
                                            msgBox("ok", "Assignment Updated");
                                        } catch (PDOException $e) {
                                            msgBox("error", $e->getMessage());
                                        }
                                    }
                                }
                                $db->query("SELECT * FROM assignments WHERE asgn_id = ?", $id);
                                $r = $db->fetchObject();
                                ?>
                                <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" value="<?php echo $r->asgn_title ?>"
                                               class="form-control" required=""/>
                                    </div>
                                    <div class="form-group">
                                        <label>Details</label>
                                        <textarea class="form-control" name="body"
                                                  rows="10"><?php echo $r->asgn_body ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="date" name="expiry" class="form-control" required=""
                                               value="<?php echo date("m/d/Y", $r->asgn_expiry) ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <?php echo assignment_status($r->asgn_status); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Course</label>
                                        <select class="form-control" name="course" disabled>
                                            <?php echo list_courses($r->course_id); ?>
                                        </select>
                                    </div>
                                    <input type="submit" name="publish" value="Update Assignment"
                                           class="btn btn-primary"/>
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
                                $db->runQuery("DELETE FROM assignments WHERE asgn_id = ?", [$id]);
                                $db->runQuery("DELETE FROM assignment_files WHERE asgn_id = ?", [$id]);
                                msgBox("ok", "Assignment Deleted");
                                header("refresh:1;url=assignments.php");
                            } catch (PDOException $e) {
                                msgBox("erorr", $e->getMessage());
                                header("refresh:3;url=assignments.php");
                            }
                        }
                    } else {
                        echo "Nothing to do";
                    }
                }
            }
        }
    }
} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Manage Assignments
                <div class="pull-right">
                    <a href="assignments.php?action=add&amp;c=<?php echo $course ?>" class="btn btn-sm btn-success">Add
                        Assignment</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Course</th>
                    <th>Assignment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    if ($_SESSION["user_level"] == "admin") {
                        $db->query('SELECT * FROM assignments WHERE course_id = ? ORDER BY asgn_id DESC', [$course]);
                    } else {
                        $db->query('SELECT * FROM assignments WHERE teacher_id = ? AND course_id = ? ORDER BY asgn_id DESC',
                            [$user_id, $course]);
                    }
                    while ($r = $db->fetchObject()) {
                        $course = $db->queryValues('SELECT * FROM courses WHERE course_id = ?', "course_name",
                            $r->course_id)->course_name;
                        $status = ($r->asgn_status == 1) ? 'Active' : 'Expired';
                        echo "
                            <tr>
                                <td>$course</td>
                                <td>$r->asgn_title</td>
                                <td>$r->asgn_date</td>
                                <td>$status</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='assignments.php?action=view&amp;id=$r->asgn_id&amp;c=$course' class='btn btn-success'>View</a>
                                        <a href='assignments.php?action=edit&amp;id=$r->asgn_id&amp;c=$course' class='btn btn-primary'>Edit</a>
                                        <a href='assignments.php?action=delete&amp;id=$r->asgn_id&amp;c=$course' class='btn btn-danger'>Delete</a>
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
<?php
require __DIR__ . '/footer.php';

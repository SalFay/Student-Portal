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
                    Add Announcement
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $title = cleanString($_POST["title"]);
                    $body = cleanString($_POST["body"]);
                    $course = cleanString($_POST["course"]);
                    if (empty($title) || empty($body) || empty($course)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $stmt = "INSERT INTO announcements(announcement_title, announcement_body, announcement_course,announcement_date) VALUES(?,?,?,CURDATE())";
                            $args = array($title, $body, $course);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Announcement Published");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                ?>
                <form method="post" action="announcements.php?action=add">
                    <div class="form-group">
                        <label>Announcement Title</label>
                        <input type="text" name="title" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Announcement Body</label>
                        <textarea class="form-control" name="body"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Announcement Course</label>
                        <select name="course" class="form-control">
                            <?php echo list_courses(); ?>
                        </select>
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
                    Edit Announcement
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (isset($_POST["publish"])) {
                    $title = cleanString($_POST["title"]);
                    $body = cleanString($_POST["body"]);
                    $course = cleanString($_POST["course"]);
                    if (empty($title) || empty($body) || empty($course)) {
                        msgBox("error", "Please provide all fields");
                    } else {
                        try {
                            $stmt = "UPDATE announcements SET announcement_title = ?,"
                                    . "announcement_body = ?, announcement_course = ?"
                                    . " WHERE announcement_id = ?";
                            $args = array($title, $body, $course,$id);
                            $db->runQuery($stmt, $args);
                            msgBox("ok", "Announcement Updated");
                        } catch (PDOException $e) {
                            msgBox("error", $e->getMessage());
                        }
                    }
                }
                $db = new Database();
                $db->query("SELECT * FROM announcements WHERE announcement_id = ?",array($id));
                $r = $db->fetchObject();
                ?>
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"] ?>">
                    <div class="form-group">
                        <label>Announcement Title</label>
                        <input type="text" name="title" value="<?php echo $r->announcement_title ?>" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <label>Announcement Body</label>
                        <textarea class="form-control" name="body"><?php echo $r->announcement_body ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Announcement Course</label>
                        <select name="course" class="form-control">
                            <?php echo list_courses($r->announcement_course); ?>
                        </select>
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
                $db->runQuery("DELETE FROM announcements WHERE announcement_id = ?", array($id));
                msgBox("ok", "Announcement Deleted");
                header("refresh:1;url=announcements.php");
            } catch (PDOException $e) {
                msgBox("erorr", $e->getMessage());
                header("refresh:3;url=announcements.php");
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
                Manage Announcements
                <div class="pull-right">
                    <a href="announcements.php?action=add" class="btn btn-sm btn-success">Add Announcement</a>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $db->query("SELECT * FROM announcements");
                    $cdb = new Database();
                    while ($r = $db->fetchObject()) {
                        $cdb->query("SELECT course_name FROM courses WHERE course_id = ?",array($r->announcement_course));
                        $course = $cdb->fetchObject()->course_name;
                        echo "
                            <tr>
                                <td>$r->announcement_title</td>
                                <td>$course</td>
                                <td>".date("D d F Y",  strtotime($r->announcement_date))."</td>
                                <td>
                                    <div class='btn-group'>
                                        <a href='announcements.php?action=edit&amp;id=$r->announcement_id' class='btn btn-primary'>Edit</a>
                                        <a href='announcements.php?action=delete&amp;id=$r->announcement_id' class='btn btn-danger'>Delete</a>
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

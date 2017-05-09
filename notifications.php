<?php
require 'header.php';
$db = new Database();
if (empty($_SESSION["std_id"])) {
    msgBox("error", "You are not logged-in");
} else {
    $user_id = $_SESSION["std_id"];
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
                        $db->query("SELECT * FROM announcements WHERE announcement_id = ?", $id);
                        $r = $db->fetchObject();
                        ?>
                        <h3><?php echo $r->announcement_title ?></h3>
                        
                        <p><?php echo $r->announcement_body ?></p>
                        
                        <?php
                    } else {
                        ?>
                        <h3>Your Announcements</h3>
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $db->query("SELECT * FROM announcements WHERE announcement_course IN(?) ORDER BY announcement_id DESC", $courses);
                            while ($r = $db->fetchObject()) {
                               echo "
                                    <tr>
                                        <td>$r->announcement_title</td>

                                        <td><a href='notifications.php?id=$r->announcement_id' class='btn btn-primary'>View</a></td>
                                    </tr>
                                ";
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

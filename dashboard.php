<?php
require 'header.php';
$semester = 0;
$department = 0;
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
                    <div class="blog-post blog-single-post">
                        <div class="single-post-title">
                            <h3>Profile</h3>
                        </div>
                        <div class="single-post-content">
                            <table class="table table-striped">
                                <?php
                                    $db = new Database();
                                    $db->query("SELECT * FROM users WHERE user_id = ?",$_SESSION["std_id"]);
                                    $r = $db->fetchObject()
                                ?>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td><?php echo $r->user_name ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?php echo $r->user_email ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td><?php echo $r->user_dob ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td><?php echo $r->user_address ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td><?php echo $r->user_gender ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="blog-post blog-single-post">
                        <div class="single-post-title">
                            <h3>Academic Information</h3>
                        </div>
                        <div class="single-post-content">
                            <table class="table table-striped">
                                <?php
                                    $db->query("SELECT * FROM admission WHERE user_id = ?",$_SESSION["std_id"]);
                                    $r = $db->fetchObject();
                                    $department = $db->queryValues("SELECT * FROM departments WHERE department_id = ?", "department_name", $r->department_id)->department_name;
                                    $semester = $r->semester_id;
                                    $cgpa = $db->queryValues("SELECT * FROM cgpa WHERE student_id = ? ORDER BY cgpa_id  DESC", "cgpa_marks", $_SESSION["std_id"])->cgpa_marks;
                                ?>
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td><?php echo $department ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Semester:</strong></td>
                                    <td><?php echo $semester ?></td>
                                </tr>
                                <tr>
                                    <td><strong>CGPA:</strong></td>
                                    <td><?php echo $cgpa ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php print_button(); ?>
                </div>
                <?php require 'sidebar-user.php'; ?>
            </div>
        </div>
    </div>
    <?php
}
require 'footer.php';

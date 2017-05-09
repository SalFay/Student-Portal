<?php
$db = new Database();
$user_id = $_SESSION["std_id"];
$db->query("SELECT * FROM admission WHERE user_id = ?", $user_id);
$r = $db->fetchObject();
$department = $r->department_id;
$semester = $r->semester_id;
?>
<div class="col-sm-4 hidden-print">
    
    <div class="blog-post blog-single-post">
        <div class="single-post-title">
            <h3>Important Links</h3>
        </div>
        <div class="single-post-content">
            <ul>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="assignments.php">Assignments</a></li>
                <li><a href="tests.php">Test Summery</a></li>
                <li><a href="exams.php">Exam Summery</a></li>
            </ul>
        </div>
    </div>
</div>
<?php require __DIR__ . '/header.php' ?>
<?php
$db = new Database();
$department = (int)cleanString($_GET['d']);
$stmt = '
    SELECT * FROM departments WHERE department_id = ?
';
$db->query($stmt, [$department]);
$dep = $db->fetchObject();
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">
            Select Semester (<?php echo $dep->department_name ?>)
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover">
            <tr>
                <th>Semester Name</th>
                <th>Action</th>
            </tr>
            <?php
            $s = $dep->department_semester;
            for ($i = 1; $i <= $s; $i++) {
                $param = '?d=' . $department . '&amp;s=' . $i;
                ?>
                <tr>
                    <td>Semester <?php echo $i ?></td>
                    <td>
                        <a href="courses.php<?php echo $param ?>" class="btn btn-primary">
                            Manage Courses
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>

<?php require __DIR__ . '/footer.php' ?>

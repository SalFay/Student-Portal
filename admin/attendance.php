<?php require __DIR__ . '/header.php' ?>
<?php
$db = new Database();
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'add') {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Take Attendance
                    <div class="pull-right">
                        <a href="attendance.php" class="btn btn-sm btn-success">All Attendance</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="panel-body">
                <?php
                if (!empty($_POST['take-attendance'])) {
                    $attendance = $_POST['std'];
                    $stmt = 'INSERT INTO attendance VALUES(null,?,?)';
                    $db->runQuery($stmt, [$_SESSION['user_id'], date('Y-m-d')]);
                    $id = $db->lastInsertId();
                    foreach ($attendance as $user => $att) {
                        $stmt = 'INSERT INTO student_attendance VALUES(null,?,?,?,?)';
                        $db->runQuery($stmt, [
                            $id,
                            $user,
                            $att['attendance'],
                            $att['reason'],
                        ]);
                    }
                    msgBox('ok', "Attendance Taken Successfully");
                }
                ?>
                <form method="post">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Student</th>
                            <th>Attendance</th>
                            <th>Absence Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $stmt = 'SELECT * FROM users where user_level = \'student\' ';
                        $db->query($stmt);
                        while ($row = $db->fetchObject()) {
                            ?>
                            <tr>
                                <td><?php echo $row->user_name ?></td>
                                <td>
                                    <select name="std[<?php echo $row->user_id ?>][attendance]" class="form-control">
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="std[<?php echo $row->user_id ?>][reason]"
                                           class="form-control"/>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">
                                <input type="submit" name="take-attendance" value="Save Attendance"
                                       class="btn btn-primary">
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
        <?php
    } elseif ($action === 'view') {
        $id = (int)$_GET['id'];
        $stmt = '
            SELECT a.date,u.user_name FROM attendance a 
            LEFT JOIN users u ON u.user_id = a.teacher_id
            WHERE a.id = ?
        ';
        $att = $db->query($stmt, [$id])->fetchObject();

        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    View Attendance of
                    <strong><?php echo date('F j, Y',strtotime($att->date)) ?></strong>
                     Taken by <strong><?php echo $att->user_name ?></strong>
                    <div class="pull-right">
                        <a href="attendance.php?action=add" class="btn btn-sm btn-success">Add Attendance</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="panel-body">
                <?php
                $stmt = '
                    SELECT sa.*,u.user_name FROM student_attendance sa 
                    LEFT JOIN users u ON u.user_id = sa.student_id 
                    WHERE sa.sheet_id = ?
                ';
                ?>
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Student</th>
                        <th>Attendance</th>
                        <th>Reason of Absence</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $db->query($stmt,[$id]);
                    while ($row = $db->fetchObject()) {
                        ?>
                        <tr>
                            <td><?php echo $row->user_name ?></td>
                            <td><?php echo $row->attendance_status ?></td>
                            <td><?php echo $row->reason ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

} else {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Manage Attendance
                <div class="pull-right">
                    <a href="attendance.php?action=add" class="btn btn-sm btn-success">Add Attendance</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = 'SELECT * FROM attendance ORDER BY "date" DESC ';
                $db->query($stmt);
                if ($_SESSION['user_level'] == 'teacher') {
                    $stmt = 'SELECT * FROM attendance WHERE teacher_id = ? ORDER BY "date" DESC';
                    $db->query($stmt, [$_SESSION['user_id']]);
                }
                while ($row = $db->fetchObject()) {
                    ?>
                    <tr>
                        <td><?php echo date('F j, Y, g:i a', strtotime($row->date)) ?></td>
                        <td>
                            <a href="attendance.php?action=view&amp;id=<?php echo $row->id ?>" class="btn btn-success">
                                View Attendance
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
require __DIR__ . '/footer.php';

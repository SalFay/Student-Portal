<?php
session_start();
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../inc/loader.php';
$user_id = 0;
if (!empty($_SESSION['std_id'])) {
    $user_id = $_SESSION['std_id'];
} elseif (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

$db = new Database();
$stmt = '
      SELECT c.message,c.message_on,u.user_name,u.user_level,u.user_id 
      FROM chat c 
      LEFT JOIN users u on u.user_id = c.user 
      ORDER BY c.message_on ASC 
        ';
$db->query($stmt);
while ($r = $db->fetchObject()) {
    $cls = $r->user_id==$user_id?'alert-info':'alert-primary';
    ?>
    <div class="alert <?php echo $cls ?>">
        <p>
            <small>
                <strong><?php echo $r->user_name ?></strong>
            </small> <em><?php echo $r->user_level ?></em> <?php echo date('F j, Y, g:i a') ?>
        </p>
        <p><?php echo $r->message ?></p>
    </div>
    <?php
}

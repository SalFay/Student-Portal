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
$message = $_POST['message'];
$db = new Database();
$stmt = 'INSERT INTO chat VALUES(NULL ,?,?,?)';
$args = [$message, $user_id, date('Y-m-d H:i:s')];
$db->runQuery($stmt,$args);
echo $db->lastInsertId();

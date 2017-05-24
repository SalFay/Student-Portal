<?php
session_start();
unset($_SESSION['std_id'],$_SESSION['std_name']);
header('location:login.php');

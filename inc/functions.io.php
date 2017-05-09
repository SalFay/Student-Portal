<?php

function cleanString($string) {
    $str = trim($string);
    $str = strip_tags($str);
    $str = htmlentities($str);
    $str = stripcslashes($str);
    return $str;
}

function msgBox($messageType, $messageText) {
    $messageType = strtolower($messageType);
    $msgType = "danger";
    $msgTitle = "Error!";
    switch($messageType){
        case 'ok':
            $msgType = "success";
            $msgTitle = "OK!";
            break;
        case 'info':
        case 'tip':
            $msgTitle = "Info!";
            $msgType = "info";
            break;
        case 'warning':
        case 'alert':
            $msgTitle = "Warning!";
            $msgType = "warning";
            break;
        default:
            break;
    }
    ?>
    <div class="alert alert-<?php echo $msgType ?> alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong><?php echo $msgTitle ?></strong> <?php echo $messageText ?>
    </div><?php
}

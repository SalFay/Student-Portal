<?php

function list_semesters($cur = 0) {
    $semesters = "";
    for ($i = 1; $i <= 8; $i ++) {
        $class = $cur == $i ? "selected=''" : "";
        $semesters .= "<option value='$i' $class>$i</option>";
    }
    return $semesters;
}

function list_departments($cur = 0) {
    $departments = "";
    $db = new Database();
    $db->query("SELECT * FROM departments");
    while ($r = $db->fetchObject()) {
        $class = $cur == $r->department_id ? "selected=''" : "";
        $departments .= "<option value='$r->department_id' $class>$r->department_name</option>";
    }
    return $departments;
}

function list_courses($cur = 0) {
    $courses = "";
    $db = new Database();
    $db->query("SELECT * FROM courses");
    while ($r = $db->fetchObject()) {
        $class = $cur == $r->course_id ? "selected=''" : "";
        $courses .= "<option value='$r->course_id' $class>$r->course_name</option>";
    }
    return $courses;
}

function list_gender($cur = "") {
    $m = ($cur === "male") ? "selected=''" : "";
    $f = ($cur === "female") ? "selected=''" : "";
    $gender = "
        <option value='male' $m>Male</option>
        <option value='female' $f>Female</option>
    ";
    return $gender;
}

function list_level($cur = "") {
    $t = ($cur === "teacher") ? "selected=''" : "";
    $s = ($cur === "student") ? "selected=''" : "";
    $a = ($cur === "admin") ? "selected=''" : "";
    $level = "
        <option value='teacher' $t>Teacher</option>
        <option value='student' $s>student</option>
        <option value='admin' $a>Admin</option>
    ";
    return $level;
}

function list_teacher($cur = 0) {
    $db = new Database();
    $teachers = "";
    try {
        $db->query("SELECT * FROM users WHERE user_level = 'teacher'");
        while ($r = $db->fetchObject()) {
            $class = $cur === $r->user_id ? "selected=''" : "";
            $teachers .= "<option $class value='$r->user_id'>$r->user_name | $r->user_login</option>";
        }
    } catch (PDOException $e) {
        msgBox("error", $e->getMessage());
    }
    return $teachers;
}

function list_student($cur = 0) {
    $db = new Database();
    $students = "";
    try {
        $db->query("SELECT * FROM users WHERE user_level = 'student'");
        while ($r = $db->fetchObject()) {
            $class = $cur === $r->user_id ? "selected=''" : "";
            $students .= "<option $class value='$r->user_id'>$r->user_name | $r->user_login</option>";
        }
    } catch (PDOException $e) {
        msgBox("error", $e->getMessage());
    }
    return $students;
}

function getCourses($department, $semester) {
    $db = new Database();
    $db->query("SELECT * FROM courses WHERE course_department = ? AND course_semester = ?", array($department, $semester));
    $courses = [];
    while ($r = $db->fetchObject()) {
        $courses[] = $r->course_id;
    }
    return $courses;
}


$gpa_info = array(
        "85" => array("gpa" => 4, "grade" => "a", "remarks" => "excellent"),
        "84" => array("gpa" => 3.9, "grade" => "b", "remarks" => "v good"),
        "83" => array("gpa" => 3.8, "grade" => "b", "remarks" => "v good"),
        "82" => array("gpa" => 3.7, "grade" => "b", "remarks" => "v good"),
        "81" => array("gpa" => 3.6, "grade" => "b", "remarks" => "v good"),
        "80" => array("gpa" => 3.5, "grade" => "b", "remarks" => "v good"),
        "79" => array("gpa" => 3.4, "grade" => "b", "remarks" => "v good"),
        "78" => array("gpa" => 3.4, "grade" => "b", "remarks" => "v good"),
        "77" => array("gpa" => 3.3, "grade" => "b", "remarks" => "v good"),
        "76" => array("gpa" => 3.3, "grade" => "b", "remarks" => "v good"),
        "75" => array("gpa" => 3.2, "grade" => "b", "remarks" => "v good"),
        "74" => array("gpa" => 3.2, "grade" => "b", "remarks" => "v good"),
        "73" => array("gpa" => 3.1, "grade" => "b", "remarks" => "v good"),
        "72" => array("gpa" => 3.0, "grade" => "b", "remarks" => "v good"),
        "71" => array("gpa" => 2.9, "grade" => "c", "remarks" => "good"),
        "70" => array("gpa" => 2.8, "grade" => "c", "remarks" => "good"),
        "69" => array("gpa" => 2.7, "grade" => "c", "remarks" => "good"),
        "68" => array("gpa" => 2.6, "grade" => "c", "remarks" => "good"),
        "67" => array("gpa" => 2.5, "grade" => "c", "remarks" => "good"),
        "66" => array("gpa" => 2.5, "grade" => "c", "remarks" => "good"),
        "65" => array("gpa" => 2.4, "grade" => "c", "remarks" => "good"),
        "64" => array("gpa" => 2.4, "grade" => "c", "remarks" => "good"),
        "63" => array("gpa" => 2.3, "grade" => "c", "remarks" => "good"),
        "62" => array("gpa" => 2.2, "grade" => "c", "remarks" => "good"),
        "61" => array("gpa" => 2.1, "grade" => "c", "remarks" => "good"),
        "60" => array("gpa" => 2.0, "grade" => "c", "remarks" => "good"),
        "59" => array("gpa" => 1.9, "grade" => "d", "remarks" => "fair"),
        "58" => array("gpa" => 1.8, "grade" => "d", "remarks" => "fair"),
        "57" => array("gpa" => 1.7, "grade" => "d", "remarks" => "fair"),
        "56" => array("gpa" => 1.6, "grade" => "d", "remarks" => "fair"),
        "55" => array("gpa" => 1.5, "grade" => "d", "remarks" => "fair"),
        "54" => array("gpa" => 1.4, "grade" => "d", "remarks" => "fair"),
        "53" => array("gpa" => 1.3, "grade" => "d", "remarks" => "fair"),
        "52" => array("gpa" => 1.2, "grade" => "d", "remarks" => "fair"),
        "51" => array("gpa" => 1.1, "grade" => "d", "remarks" => "fair"),
        "50" => array("gpa" => 1.0, "grade" => "d", "remarks" => "fair"),
        "49" => array("gpa" => 0, "grade" => "f", "remarks" => "fail"),
    );
// GPA & CPGA FUNCtiONS
function getGpa($percent) {
    global $gpa_info;
    $gpa = 0;
    if($percent > 85){
        $gpa = 4;
    }else if($percent <= 49){
        $gpa = 0;
    }else{
        $gpa = $gpa_info[$percent]["gpa"];
    }
    return $gpa;
}


function print_button(){
    ?>
<div class="hidden-print">
    <button type="button" onclick="print_it()" class="btn btn-primary btn-block btn-lg">Print</button>
</div>
<?php
}

function assignment_status($cur = 0){
    $a = ($cur == 1)?" selected=''":"";
    $e = ($cur == 0)?" selected=''":"";
    return "
        <option value='1'$a>Active</option>
        <option value='0'$e>Expired</option>
    ";
}
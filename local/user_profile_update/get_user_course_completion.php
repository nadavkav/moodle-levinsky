<?php
//define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');

//$courseid = optional_param('courseid', 2, PARAM_INT);
$useridnumber = optional_param('useridnumber', 2, PARAM_INT);

$user = $DB->get_record('user', array('idnumber' => $useridnumber, 'deleted' => 0), '*', MUST_EXIST);
switch ($user->department) {
    case 'management':
        $courseid = 17532;
        break;
    case 'teacher':
        $courseid = 17529;
        break;
    case 'student':
        $courseid = 17533;
        break;
}
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

// Grab all courses the user is enrolled in and their completion status
$sql = "
    SELECT DISTINCT
        c.id AS id
    FROM
        {course} c
    INNER JOIN
        {context} con
     ON con.instanceid = c.id
    INNER JOIN
        {role_assignments} ra
     ON ra.contextid = con.id
    INNER JOIN
        {enrol} e
     ON c.id = e.courseid
    INNER JOIN
        {user_enrolments} ue
     ON e.id = ue.enrolid AND ra.userid = ue.userid
    AND ra.userid = {$user->id}
";

// Get roles that are tracked by course completion
if ($roles = $CFG->gradebookroles) {
    $sql .= '
        AND ra.roleid IN (' . $roles . ')
    ';
}

$sql .= '
    WHERE
        con.contextlevel = ' . CONTEXT_COURSE . '
    AND c.enablecompletion = 1
';


// If we are looking at a specific course
if ($course->id != 1) {
    $sql .= '
        AND c.id = ' . (int)$course->id . '
    ';
}

// Check if result is empty
$rs = $DB->get_recordset_sql($sql);
if (!$rs->valid()) {

    if ($course->id != 1) {
        $error = get_string('nocompletions', 'report_completion'); // TODO: missing string
    } else {
        $error = get_string('nocompletioncoursesenroled', 'report_completion'); // TODO: missing string
    }
    echo 'error';
    //echo $OUTPUT->notification($error);
    $rs->close(); // not going to loop (but break), close rs
    //echo $OUTPUT->footer();
    die();
}

// Categorize courses by their status
$courses = array(
    'inprogress' => array(),
    'complete' => array(),
    'unstarted' => array()
);

// Sort courses by the user's status in each
foreach ($rs as $course_completion) {
    $c_info = new completion_info((object)$course_completion);

    // Is course complete?
    $coursecomplete = $c_info->is_course_complete($user->id);

    // Has this user completed any criteria?
    $criteriacomplete = $c_info->count_course_user_data($user->id);

    if ($coursecomplete) {
        $courses['complete'][] = $c_info;
    } else if ($criteriacomplete) {
        $courses['inprogress'][] = $c_info;
    } else {
        $courses['unstarted'][] = $c_info;
    }
}
$rs->close(); // after loop, close rs

// debug
//print_object($courses['complete']);

$iscoursecompleted = false;
foreach ($courses['complete'] as $completedcourse) {
    if ($courseid == $completedcourse->course_id) $iscoursecompleted = true;
    //echo 'Completed courseid = '.$completedcourse->course_id. PHP_EOL;
}
if ($iscoursecompleted) {
    echo 'course-completed=true';
} else {
    //echo 'course-completed=false';
    echo 'courseurl=https://moodle.levinsky.ac.il/course/view.php?id=' . $courseid;
}


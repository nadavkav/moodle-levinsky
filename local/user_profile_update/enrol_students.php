<?php
define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');

if (!$enrol_manual = enrol_get_plugin('manual')) {
    throw new coding_exception('Can not instantiate enrol_manual');
}

$instance = $DB->get_record('enrol', array('courseid' => '17533', 'enrol' => 'manual'), '*', MUST_EXIST);

$sql = "SELECT DISTINCT u.username, u.id 
FROM mdl_course AS c
       JOIN mdl_context AS ctx ON  ctx.instanceid=c.id AND ctx.contextlevel = 50
       JOIN mdl_role_assignments AS ra ON ra.contextid = ctx.id
       JOIN mdl_user AS u ON u.id = ra.userid
WHERE ra.roleid = 5 AND u.deleted = 0 AND c.id >= 14897 AND u.department = 'student'
GROUP BY u.username";

$users = $DB->get_recordset_sql($sql);

foreach ($users as $user) {
    if ($user->id)
        $enrol_manual->enrol_user($instance, $user->id, 5, time()); // enrol as 'student' = 5
    echo ".";
}
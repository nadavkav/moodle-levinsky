<?php

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
$CFG->debug = 32767;
$CFG->debugdeveloper = true;
$CFG->debugdisplay = true;
set_debugging(DEBUG_DEVELOPER, true);

global $DB;
//$DB->set_debug(true);
$CFG->extramemorylimit = '1024M';
if (function_exists("raise_memory_limit")) {
    core_php_time_limit::raise();
    //raise_memory_limit(MEMORY_UNLIMITED);
    raise_memory_limit(MEMORY_HUGE);
}

$helper = new \local_user_profile_update\observer();
//echo 'Starting...';die;
/*
$sql = "SELECT *
          FROM {user}
         WHERE deleted = 0 AND department = '' ";
*/

// get all teachers in courses
$sql = "SELECT DISTINCT u.username, u.id
  FROM mdl_course AS c
    JOIN mdl_context AS ctx ON  ctx.instanceid=c.id AND ctx.contextlevel = 50
    JOIN mdl_role_assignments AS ra ON ra.contextid = ctx.id
    JOIN mdl_user AS u ON u.id = ra.userid
WHERE ra.roleid = 3 AND u.deleted = 0 AND u.id > 20176
GROUP BY u.username 
ORDER BY u.id ";

$users = $DB->get_recordset_sql($sql, null, 0, 42000);

while($users->valid()) {
    $user = $users->current();
    $usertype = $helper::update_usertype($user->id);
    echo "Updated userid = $user->id ". PHP_EOL;
    //echo " $user->id ". PHP_EOL;
    //$updateduser = $DB->get_record('user', array('id' => $user->id));
    //echo ' (department=' . $updateduser->department . ') <br> ' . PHP_EOL;
    //echo 'mem='.memory_get_usage(). PHP_EOL;
    $users->next();
}
$rs->close();

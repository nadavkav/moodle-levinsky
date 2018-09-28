<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of functions and constants for module streamitup
 *
 * @package mod_streamitup
 * @copyright  2003 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->libdir . '/phpunit/classes/util.php');

$courseid = optional_param('courseid', 2, PARAM_INT);   // course

$streamitup_wp = 'http://www.streamitupcdn.net/levinsky_db/database/get_channel_details_api.php';

// WS optional params:
/*
מזהה הקורס - id_get
סיסמת הקורס - password_get
שם הקורס - name_get
תאריך יצירת הקורס - date_start_get
מייל אחראי הקורס - email_get
מידע טקסטואלי נוסף על הקורס - comment_get
הכיתה/כיתות אליהם הקורס משויך - class_main_get
מזהה הקורס במערכת המודל הארגוני - info_lms_get
מספר הצפיות בקורס זה – views_of_num_g
*/

//$courseid = 15718;

// TODO: use Moodle cURL (that uses Moodle's proxy)
$curlConfig = array(
    CURLOPT_URL => $streamitup_wp,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => array('moodle_course_id' => $courseid,
        //CURLOPT_POSTFIELDS => array('moodle_course_id' => $COURSE->id,
        //CURLOPT_POSTFIELDS => array(
        'get_id' => 1, 'get_name' => '1', 'get_password' => '1', 'get_lms_info' => '1', 'get_main_class' => '1'),
);
$ch = curl_init();
curl_setopt_array($ch, $curlConfig);

$result = curl_exec($ch);
curl_close($ch);
//var_dump(json_decode($result));
$result_decoded = json_decode($result);
print_object($result_decoded);

if ($result_decoded->msg[0]->id) {
    $streamitup_instance = $DB->get_record_sql('SELECT * FROM {course_modules} AS cm 
        JOIN {modules} AS m ON m.id = cm.module 
        WHERE cm.course = ? AND m.name = "streamitup" AND cm.deletioninprogress = 0',
        array($courseid));
    if ($streamitup_instance) {
        echo 'StreamITup was found';
    } else {
        echo 'Needs to add StreamITup instance to course...';
        //die;
        $generator = \phpunit_util::get_data_generator();
        $streamitupgenerator = $generator->get_plugin_generator('mod_streamitup');
        $options = array('section' => 0, 'intro' => 'Watch session recordings');
        $streamitup = $streamitupgenerator->create_instance(array('course' => $courseid), $options);
        echo "\nAdded !";
        // Create empty forum.
        //$streamitupgenerator = $generator->get_plugin_generator('mod_streamitup');
        //$record = array('course' => $courseid, 'name' => get_string('pluginname', 'mod_streamitup'));
        //$options = array('section' => 0);
        //$streamitup = $streamitupgenerator->create_instance($record, $options);
    }
}



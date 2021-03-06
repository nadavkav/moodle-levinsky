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
 * Prints a particular instance of videofile
 *
 * @package    mod_videofile
 * @copyright  2015 Jonas Nockert <jonasnockert@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once($CFG->libdir . '/resourcelib.php');

$id       = required_param('id', PARAM_INT);
$redirect = optional_param('redirect', 0, PARAM_BOOL);

$cm = get_coursemodule_from_id('videofile', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$context = context_module::instance($cm->id);
$videofile = new videofile($context, $cm, $course);

require_login($course, true, $cm);
require_capability('mod/videofile:view', $context);

if ($redirect) {
    $PAGE->set_pagelayout('popup');
}

switch ($videofile->get_instance()->display) {
    case RESOURCELIB_DISPLAY_EMBED:
        $PAGE->set_pagelayout('embedded');
        break;
    default:
        $PAGE->set_pagelayout('incourse');
        break;
}

$url = new moodle_url('/mod/videofile/view.php', array('id' => $id));
$PAGE->set_url('/mod/videofile/view.php', array('id' => $cm->id));

// Update 'viewed' state if required by completion system.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Log viewing.
if (class_exists('\core\event\course_module_viewed')) {
    $event = \mod_videofile\event\course_module_viewed::create(array(
        'objectid' => $cm->instance,
        'context' => $PAGE->context,
    ));
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot($cm->modname, $videofile->get_instance());
    $event->trigger();
} else {
    // Legacy logging. Deprecated in Moodle 2.7.
    add_to_log($course->id,
               'videofile',
               'view',
               'view.php?id=' . $cm->id,
               $videofile->get_instance()->id, $cm->id);
}

$renderer = $PAGE->get_renderer('mod_videofile');
echo $renderer->video_page($videofile);
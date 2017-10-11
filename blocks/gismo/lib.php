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

defined('MOODLE_INTERNAL') || die();

/**
 * GISMO block
 *
 * @package    block_gismo
 * @copyright  eLab Christian Milani
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * This function extends the course settings navigation block, if in a course
 * and have correct permissions a link to gismo block page
 * will be added.
 */
function block_gismo_extend_navigation_course($navigation, $course, $context) {
    global $SITE;

    if (!isloggedin()) {
        return;
    }

    if (is_null($navigation) or is_null($context)) {
        return;
    }

    if ($context->instanceid === $SITE->id) {
        return;
    }

    if (!has_capability('block/gismo:view', $context)) {
        //return;
    }

    // server data
    $data = new stdClass();
    $data->block_instance_id = $context->instanceid;
    $data->course_id = $course->id;
    $srv_data_encoded = urlencode(base64_encode(serialize($data)));

    // Only add link when in the context of a course.
    if ($context instanceof context_course) {
        if ($reports = $navigation->get('coursereports')) {
            $url = new moodle_url('/blocks/gismo/main.php', array('srv_data' => $srv_data_encoded));
            $reports->add(get_string('gismo_report_launch', 'block_gismo'), $url, navigation_node::TYPE_CUSTOM,
                null, 'localbulkmeta', new pix_icon('i/scales', get_string('gismo_report_launch', 'block_gismo'), 'core'));

        }
    }

}

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
 * streamitup external API
 *
 * @package    mod_streamitup
 * @category   external
 * @copyright  2017 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.3
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

/**
 * streamitup external functions
 *
 * @package    mod_streamitup
 * @category   external
 * @copyright  2017 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.3
 */
class mod_streamitup_external extends external_api {

    /**
     * Describes the parameters for get_streamitups_by_courses.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function get_streamitups_by_courses_parameters() {
        return new external_function_parameters (
            array(
                'courseids' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'Course id'), 'Array of course ids', VALUE_DEFAULT, array()
                ),
            )
        );
    }

    /**
     * Returns a list of streamitups in a provided list of courses.
     * If no list is provided all streamitups that the user can view will be returned.
     *
     * @param array $courseids course ids
     * @return array of warnings and streamitups
     * @since Moodle 3.3
     */
    public static function get_streamitups_by_courses($courseids = array()) {

        $warnings = array();
        $returnedstreamitups = array();

        $params = array(
            'courseids' => $courseids,
        );
        $params = self::validate_parameters(self::get_streamitups_by_courses_parameters(), $params);

        $mycourses = array();
        if (empty($params['courseids'])) {
            $mycourses = enrol_get_my_courses();
            $params['courseids'] = array_keys($mycourses);
        }

        // Ensure there are courseids to loop through.
        if (!empty($params['courseids'])) {

            list($courses, $warnings) = external_util::validate_courses($params['courseids'], $mycourses);

            // Get the streamitups in this course, this function checks users visibility permissions.
            // We can avoid then additional validate_context calls.
            $streamitups = get_all_instances_in_courses("streamitup", $courses);
            foreach ($streamitups as $streamitup) {
                $context = context_module::instance($streamitup->coursemodule);
                // Entry to return.
                $streamitup->name = external_format_string($streamitup->name, $context->id);

                list($streamitup->intro, $streamitup->introformat) = external_format_text($streamitup->intro,
                                                                $streamitup->introformat, $context->id, 'mod_streamitup', 'intro', null);
                $streamitup->introfiles = external_util::get_area_files($context->id, 'mod_streamitup', 'intro', false, false);

                $returnedstreamitups[] = $streamitup;
            }
        }

        $result = array(
            'streamitups' => $returnedstreamitups,
            'warnings' => $warnings
        );
        return $result;
    }

    /**
     * Describes the get_streamitups_by_courses return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_streamitups_by_courses_returns() {
        return new external_single_structure(
            array(
                'streamitups' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Module id'),
                            'coursemodule' => new external_value(PARAM_INT, 'Course module id'),
                            'course' => new external_value(PARAM_INT, 'Course id'),
                            'name' => new external_value(PARAM_RAW, 'streamitup name'),
                            'intro' => new external_value(PARAM_RAW, 'streamitup contents'),
                            'introformat' => new external_format_value('intro', 'Content format'),
                            'introfiles' => new external_files('Files in the introduction text'),
                            'timemodified' => new external_value(PARAM_INT, 'Last time the streamitup was modified'),
                            'section' => new external_value(PARAM_INT, 'Course section id'),
                            'visible' => new external_value(PARAM_INT, 'Module visibility'),
                            'groupmode' => new external_value(PARAM_INT, 'Group mode'),
                            'groupingid' => new external_value(PARAM_INT, 'Grouping id'),
                        )
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }
}

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
 * Class containing data for my overview block.
 *
 * @package    block_myoverview
 * @copyright  2017 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_myoverview\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use core_completion\progress;

require_once($CFG->dirroot . '/blocks/myoverview/lib.php');
require_once($CFG->libdir . '/completionlib.php');

/**
 * Class containing data for my overview block.
 *
 * @copyright  2017 Simey Lameze <simey@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main implements renderable, templatable {

    /**
     * @var string The tab to display.
     */
    public $tab;

    /**
     * Constructor.
     *
     * @param string $tab The tab to display.
     */
    public function __construct($tab) {
        $this->tab = $tab;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $USER;

        $courses = enrol_get_my_courses('*');
        $coursesprogress = [];

        // Filter courses by Year and Semester.
        $fcb_year = optional_param('fcb_year', null, PARAM_RAW);
        $fcb_semester = optional_param('fcb_semester', null, PARAM_RAW);

        $filter_years = get_config('block_myoverview', 'filter_years');
        //$yearoptions[] = array('yearcode' => '', 'yearname' => get_string('choose'));
        foreach (explode("\r\n", $filter_years) as $yearrow) {
            list($yearcode ,$yearname) = explode('=', $yearrow);
            if ($fcb_year == $yearcode) {
                $yearoptions[] = array('yearcode' => $yearcode, 'yearname' => $yearname, 'selected' => 'selected');
            } else {
                $yearoptions[] = array('yearcode' => $yearcode, 'yearname' => $yearname);
            }
        }

        $filter_semesters = get_config('block_myoverview', 'filter_semesters');
        //$semesteroptions[] = array('semestercode' => '', 'semestername' => get_string('choose'));
        foreach (explode("\r\n", $filter_semesters) as $semesterrow) {
            list($semestercode ,$semestername) = explode('=', $semesterrow);
            if ($fcb_semester == $semestercode) {
                $semesteroptions[] = array('semestercode' => $semestercode, 'semestername' => $semestername, 'selected' => 'selected');
            } else {
                $semesteroptions[] = array('semestercode' => $semestercode, 'semestername' => $semestername);
            }
        }

        $filter_full = $fcb_year;
        if ($fcb_semester !== 'all' && $fcb_semester !== null) {
            $filter_full = $fcb_year.'_'.$fcb_semester;
        }
        if ($fcb_semester === 'all' && $fcb_year === 'all') {
            $filter_full = '';
        }
        if ($fcb_semester === '' && $fcb_year === '') {
            $filter_full = '___';
        }
        // Remove courses which are not chosen by Category / Role / Semester
        $filter_coursefield = get_config('block_myoverview', 'filter_coursefield');
        foreach ($courses as $key => $course) {

            if (null != $filter_full && strpos($course->{$filter_coursefield}, $filter_full) === false) {
                unset($courses[$key]);
            }

            /*
            $course->context = context_course::instance($course->id, MUST_EXIST);
            if ($filterbyrole > 0 && !user_has_role_assignment($USER->id, $filterbyrole, $course->context->id)){
                //continue;
                unset($courses[$key]);
            }
            */
        }

        foreach ($courses as $course) {

            $completion = new \completion_info($course);

            // First, let's make sure completion is enabled.
            if (!$completion->is_enabled()) {
                continue;
            }

            $percentage = progress::get_course_progress_percentage($course);
            if (!is_null($percentage)) {
                $percentage = floor($percentage);
            }

            $coursesprogress[$course->id]['completed'] = $completion->is_course_complete($USER->id);
            $coursesprogress[$course->id]['progress'] = $percentage;
        }

        $coursesview = new courses_view($courses, $coursesprogress);
        $nocoursesurl = $output->image_url('courses', 'block_myoverview')->out();
        $noeventsurl = $output->image_url('activities', 'block_myoverview')->out();

        // Now, set the tab we are going to be viewing.
        $viewingtimeline = false;
        $viewingcourses = false;
        if ($this->tab == BLOCK_MYOVERVIEW_TIMELINE_VIEW) {
            $viewingtimeline = true;
        } else {
            $viewingcourses = true;
        }

        return [
            'midnight' => usergetmidnight(time()),
            'coursesview' => $coursesview->export_for_template($output),
            'urls' => [
                'nocourses' => $nocoursesurl,
                'noevents' => $noeventsurl
            ],
            'viewingtimeline' => $viewingtimeline,
            'viewingcourses' => $viewingcourses,
            // Used by theme/fordson myoverview templates.
            'filteryears' => $yearoptions,
            'filtersemesters' => $semesteroptions
        ];
    }
}

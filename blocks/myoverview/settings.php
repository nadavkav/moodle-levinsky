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
 * Settings for the overview block.
 *
 * @package    block_myoverview
 * @copyright  2017 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/blocks/myoverview/lib.php');

if ($ADMIN->fulltree) {

    $options = [
        BLOCK_MYOVERVIEW_TIMELINE_VIEW => get_string('timeline', 'block_myoverview'),
        BLOCK_MYOVERVIEW_COURSES_VIEW => get_string('courses')
    ];

    $settings->add(new admin_setting_configselect('block_myoverview/defaulttab',
        get_string('defaulttab', 'block_myoverview'),
        get_string('defaulttab_desc', 'block_myoverview'), 'timeline', $options));

    // Filter years and semesters.
    $setting = new admin_setting_configtextarea('block_myoverview/filter_years',
        get_string('filter_years', 'block_myoverview'),
        get_string('filter_yearshelp', 'block_myoverview'),
        'default');
    $setting->set_force_ltr(false);
    $settings->add($setting);

    $settings->add(new admin_setting_configtext('block_myoverview/filter_yearsdefault',
        get_string('filter_yearsdefault', 'block_myoverview'),
        get_string('filter_yearsdefaulthelp', 'block_myoverview'),
        ''));

    $setting = new admin_setting_configtextarea('block_myoverview/filter_semesters',
        get_string('filter_semesters', 'block_myoverview'),
        get_string('filter_semestershelp', 'block_myoverview'),
        'default');
    $setting->set_force_ltr(false);
    $settings->add($setting);

    $settings->add(new admin_setting_configtext('block_myoverview/filter_coursefield',
        get_string('filter_coursefield', 'block_myoverview'),
        get_string('filter_coursefieldhelp', 'block_myoverview'),
        'idnumber'));
    $settings->add(new admin_setting_configtext('block_myoverview/filter_startdategrace',
        get_string('filter_startdategrace', 'block_myoverview'),
        get_string('filter_startdategracehelp', 'block_myoverview'),
        '14'));
}

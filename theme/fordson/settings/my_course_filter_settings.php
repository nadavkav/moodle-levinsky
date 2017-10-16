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
 * My courses filter settings page file.
 * (Moodle 3.1 block_course_overview)
 *
 * @package    theme_fordson
 * @copyright  2016 Chris Kenniburg
 * @credits    theme_boost - MoodleHQ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_fordson_my_courses_filter', get_string('mycoursesfilter_settings', 'theme_fordson'));

// Filtered courselist setting (custom plugin)
//$categories = make_categories_options();
$setting = new admin_setting_heading('theme_fordson/filtercourses', get_string('filtercourses', 'theme_fordson'),
    get_string('filtercourses_desc', 'theme_fordson'));
$page->add($setting);

if (file_exists($CFG->libdir. '/coursecatlib.php') ) {
    require_once($CFG->libdir. '/coursecatlib.php');
    $categories = coursecat::make_categories_list();
    $categories['-1'] = get_string('navshowallcourses','theme_fordson');
    //$frontpage = $ADMIN->locate('frontpage');
    $setting = new admin_setting_configselect('theme_fordson/defaultcoursecategroy',get_string('defaultcoursecategroy', 'theme_fordson'),
        get_string('defaultcoursecategroydescription', 'theme_fordson'),'-1', $categories);
    $page->add($setting);

    $setting = new admin_setting_configcheckbox('theme_fordson/showonlytopcategories', get_string('showonlytopcategories', 'theme_fordson'),
        get_string('showonlytopcategoriesdescription', 'theme_fordson'), 0);
    $page->add($setting);

    $setting = new admin_setting_configcheckbox('theme_fordson/sortcoursesbylastaccess', get_string('sortcoursesbylastaccess', 'theme_fordson'),
        get_string('sortcoursesbylastaccessdescription', 'theme_fordson'), 0);
    $page->add($setting);
}


// Must add the page after definiting all the settings!
$settings->add($page);

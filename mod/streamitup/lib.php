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
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/** STREAMITUP_MAX_NAME_LENGTH = 50 */
define("STREAMITUP_MAX_NAME_LENGTH", 50);

/**
 * @uses STREAMITUP_MAX_NAME_LENGTH
 * @param object $streamitup
 * @return string
 */
function get_streamitup_name($streamitup) {
    $name = strip_tags(format_string($streamitup->intro,true));
    if (core_text::strlen($name) > STREAMITUP_MAX_NAME_LENGTH) {
        $name = core_text::substr($name, 0, STREAMITUP_MAX_NAME_LENGTH)."...";
    }

    if (empty($name)) {
        // arbitrary name
        $name = get_string('modulename','streamitup');
    }

    return $name;
}
/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @global object
 * @param object $streamitup
 * @return bool|int
 */
function streamitup_add_instance($streamitup) {
    global $DB;

    $streamitup->name = get_streamitup_name($streamitup);
    $streamitup->timemodified = time();
    //$streamitup->visible = 0; // Initially, not available or visible to students.

    $id = $DB->insert_record("streamitup", $streamitup);

    $completiontimeexpected = !empty($streamitup->completionexpected) ? $streamitup->completionexpected : null;
    \core_completion\api::update_completion_date_event($streamitup->coursemodule, 'streamitup', $id, $completiontimeexpected);

    // Initially, not available or visible to students.
    //$visible = ($action === 'hide') ? 0 : 1;
    //$visibleoncoursepage = ($action === 'stealth') ? 0 : 1;
    //set_coursemodule_visible($id, 0);

    return $id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @global object
 * @param object $streamitup
 * @return bool
 */
function streamitup_update_instance($streamitup) {
    global $DB;

    $streamitup->name = get_streamitup_name($streamitup);
    $streamitup->timemodified = time();
    $streamitup->id = $streamitup->instance;

    $completiontimeexpected = !empty($streamitup->completionexpected) ? $streamitup->completionexpected : null;
    \core_completion\api::update_completion_date_event($streamitup->coursemodule, 'streamitup', $streamitup->id, $completiontimeexpected);

    return $DB->update_record("streamitup", $streamitup);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @global object
 * @param int $id
 * @return bool
 */
function streamitup_delete_instance($id) {
    global $DB;

    if (! $streamitup = $DB->get_record("streamitup", array("id"=>$id))) {
        return false;
    }

    $result = true;

    $cm = get_coursemodule_from_instance('streamitup', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'streamitup', $streamitup->id, null);

    if (! $DB->delete_records("streamitup", array("id"=>$streamitup->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @global object
 * @param object $coursemodule
 * @return cached_cm_info|null
 */
function streamitup_get_coursemodule_info($coursemodule) {
    global $DB;

    if ($streamitup = $DB->get_record('streamitup', array('id'=>$coursemodule->instance), 'id, name, intro, introformat')) {
        if (empty($streamitup->name)) {
            // streamitup name missing, fix it
            $streamitup->name = "streamitup{$streamitup->id}";
            $DB->set_field('streamitup', 'name', $streamitup->name, array('id'=>$streamitup->id));
        }
        $info = new cached_cm_info();
        // no filtering here because this info is cached and filtered later
        $info_content = format_module_intro('streamitup', $streamitup, $coursemodule->id, false);
        $info->content = streamitup_recorsing_sessions_button(). '<br/>' . $info_content;
        $info->name  = $streamitup->name;
        return $info;
    } else {
        return null;
    }
}

function streamitup_recorsing_sessions_button() {
    global $COURSE, $OUTPUT;

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

    // TODO: use Moodle cURL (that uses Moodle's proxy)
    $curlConfig = array(
        CURLOPT_URL => $streamitup_wp,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_POSTFIELDS => array('moodle_course_id' => '14877',
        CURLOPT_POSTFIELDS => array('moodle_course_id' => $COURSE->id,
            'get_id' => 1, 'get_name' => '1', 'get_password' => '1', 'get_lms_info' => '1', 'get_main_class' => '1'),
    );
    $ch = curl_init();
    curl_setopt_array($ch, $curlConfig);

    $result = curl_exec($ch);
    curl_close($ch);
    //var_dump(json_decode($result));
    $result_decoded = json_decode($result);
    //print_object($result_decoded);die;

    $params['username'] = $result_decoded->msg[0]->id;
    $params['password'] = $result_decoded->msg[0]->password;

    if ($result_decoded->msg[0]->id) {
        return $OUTPUT->render_from_template('streamitup/course_recoring_sessions', $params);
    } else {
        return get_string('nosessionsavailable', 'mod_streamitup');
    }

}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 *
 * @param object $data the data submitted from the reset course.
 * @return array status array
 */
function streamitup_reset_userdata($data) {
    return array();
}

/**
 * Returns all other caps used in module
 *
 * @return array
 */
function streamitup_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

/**
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function streamitup_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:                return false;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_NO_VIEW_LINK:            return true;

        default: return null;
    }
}

/**
 * Register the ability to handle drag and drop file uploads
 * @return array containing details of the files / types the mod can handle
 */
function streamitup_dndupload_register() {
    $strdnd = get_string('dnduploadstreamitup', 'mod_streamitup');
    if (get_config('streamitup', 'dndmedia')) {
        $mediaextensions = file_get_typegroup('extension', ['web_image', 'web_video', 'web_audio']);
        $files = array();
        foreach ($mediaextensions as $extn) {
            $extn = trim($extn, '.');
            $files[] = array('extension' => $extn, 'message' => $strdnd);
        }
        $ret = array('files' => $files);
    } else {
        $ret = array();
    }

    $strdndtext = get_string('dnduploadstreamituptext', 'mod_streamitup');
    return array_merge($ret, array('types' => array(
        array('identifier' => 'text/html', 'message' => $strdndtext, 'noname' => true),
        array('identifier' => 'text', 'message' => $strdndtext, 'noname' => true)
    )));
}

/**
 * Handle a file that has been uploaded
 * @param object $uploadinfo details of the file / content that has been uploaded
 * @return int instance id of the newly created mod
 */
function streamitup_dndupload_handle($uploadinfo) {
    global $USER;

    // Gather the required info.
    $data = new stdClass();
    $data->course = $uploadinfo->course->id;
    $data->name = $uploadinfo->displayname;
    $data->intro = '';
    $data->introformat = FORMAT_HTML;
    $data->coursemodule = $uploadinfo->coursemodule;

    // Extract the first (and only) file from the file area and add it to the streamitup as an img tag.
    if (!empty($uploadinfo->draftitemid)) {
        $fs = get_file_storage();
        $draftcontext = context_user::instance($USER->id);
        $context = context_module::instance($uploadinfo->coursemodule);
        $files = $fs->get_area_files($draftcontext->id, 'user', 'draft', $uploadinfo->draftitemid, '', false);
        if ($file = reset($files)) {
            if (file_mimetype_in_typegroup($file->get_mimetype(), 'web_image')) {
                // It is an image - resize it, if too big, then insert the img tag.
                $config = get_config('streamitup');
                $data->intro = streamitup_generate_resized_image($file, $config->dndresizewidth, $config->dndresizeheight);
            } else {
                // We aren't supposed to be supporting non-image types here, but fallback to adding a link, just in case.
                $url = moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename());
                $data->intro = html_writer::link($url, $file->get_filename());
            }
            $data->intro = file_save_draft_area_files($uploadinfo->draftitemid, $context->id, 'mod_streamitup', 'intro', 0,
                                                      null, $data->intro);
        }
    } else if (!empty($uploadinfo->content)) {
        $data->intro = $uploadinfo->content;
        if ($uploadinfo->type != 'text/html') {
            $data->introformat = FORMAT_PLAIN;
        }
    }

    return streamitup_add_instance($data, null);
}

/**
 * Resize the image, if required, then generate an img tag and, if required, a link to the full-size image
 * @param stored_file $file the image file to process
 * @param int $maxwidth the maximum width allowed for the image
 * @param int $maxheight the maximum height allowed for the image
 * @return string HTML fragment to add to the streamitup
 */
function streamitup_generate_resized_image(stored_file $file, $maxwidth, $maxheight) {
    global $CFG;

    $fullurl = moodle_url::make_draftfile_url($file->get_itemid(), $file->get_filepath(), $file->get_filename());
    $link = null;
    $attrib = array('alt' => $file->get_filename(), 'src' => $fullurl);

    if ($imginfo = $file->get_imageinfo()) {
        // Work out the new width / height, bounded by maxwidth / maxheight
        $width = $imginfo['width'];
        $height = $imginfo['height'];
        if (!empty($maxwidth) && $width > $maxwidth) {
            $height *= (float)$maxwidth / $width;
            $width = $maxwidth;
        }
        if (!empty($maxheight) && $height > $maxheight) {
            $width *= (float)$maxheight / $height;
            $height = $maxheight;
        }

        $attrib['width'] = $width;
        $attrib['height'] = $height;

        // If the size has changed and the image is of a suitable mime type, generate a smaller version
        if ($width != $imginfo['width']) {
            $mimetype = $file->get_mimetype();
            if ($mimetype === 'image/gif' or $mimetype === 'image/jpeg' or $mimetype === 'image/png') {
                require_once($CFG->libdir.'/gdlib.php');
                $data = $file->generate_image_thumbnail($width, $height);

                if (!empty($data)) {
                    $fs = get_file_storage();
                    $record = array(
                        'contextid' => $file->get_contextid(),
                        'component' => $file->get_component(),
                        'filearea'  => $file->get_filearea(),
                        'itemid'    => $file->get_itemid(),
                        'filepath'  => '/',
                        'filename'  => 's_'.$file->get_filename(),
                    );
                    $smallfile = $fs->create_file_from_string($record, $data);

                    // Replace the image 'src' with the resized file and link to the original
                    $attrib['src'] = moodle_url::make_draftfile_url($smallfile->get_itemid(), $smallfile->get_filepath(),
                                                                    $smallfile->get_filename());
                    $link = $fullurl;
                }
            }
        }

    } else {
        // Assume this is an image type that get_imageinfo cannot handle (e.g. SVG)
        $attrib['width'] = $maxwidth;
    }

    $img = html_writer::empty_tag('img', $attrib);
    if ($link) {
        return html_writer::link($link, $img);
    } else {
        return $img;
    }
}

/**
 * Check if the module has any update that affects the current user since a given time.
 *
 * @param  cm_info $cm course module data
 * @param  int $from the time to check updates from
 * @param  array $filter  if we need to check only specific updates
 * @return stdClass an object with the different type of areas indicating if they were updated or not
 * @since Moodle 3.2
 */
function streamitup_check_updates_since(cm_info $cm, $from, $filter = array()) {
    $updates = course_check_module_updates_since($cm, $from, array(), $filter);
    return $updates;
}

/**
 * This function receives a calendar event and returns the action associated with it, or null if there is none.
 *
 * This is used by block_myoverview in order to display the event appropriately. If null is returned then the event
 * is not displayed on the block.
 *
 * @param calendar_event $event
 * @param \core_calendar\action_factory $factory
 * @return \core_calendar\local\event\entities\action_interface|null
 */
function mod_streamitup_core_calendar_provide_event_action(calendar_event $event,
                                                      \core_calendar\action_factory $factory) {
    $cm = get_fast_modinfo($event->courseid)->instances['streamitup'][$event->instance];

    $completion = new \completion_info($cm->get_course());

    $completiondata = $completion->get_data($cm, false);

    if ($completiondata->completionstate != COMPLETION_INCOMPLETE) {
        return null;
    }

    return $factory->create_instance(
        get_string('view'),
        new \moodle_url('/mod/streamitup/view.php', ['id' => $cm->id]),
        1,
        true
    );
}

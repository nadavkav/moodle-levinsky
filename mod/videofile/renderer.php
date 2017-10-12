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
 * Videofile module renderering methods are defined here.
 *
 * @package    mod_videofile
 * @copyright  2013 Jonas Nockert <jonasnockert@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/videofile/locallib.php');

/**
 * Videofile module renderer class
 */
class mod_videofile_renderer extends plugin_renderer_base {

    /**
     * Renders the videofile page header.
     *
     * @param videofile videofile
     * @return string
     */
    public function video_header($videofile) {
        global $CFG;

        $output = '';

        $name = format_string($videofile->get_instance()->name,
                              true,
                              $videofile->get_course());
        $title = $this->page->course->shortname . ': ' . $name;

        $coursemoduleid = $videofile->get_course_module()->id;
        $context = context_module::instance($coursemoduleid);

        // Add videojs css and js files.
        //$this->page->requires->css('/mod/videofile/video-js-4.12.8/video-js.min.css');
        //$this->page->requires->js('/mod/videofile/video-js-4.12.8/video.js', true);
        // Accessibility support
        $this->page->requires->css('/mod/videofile/video-js-5.21.1/video-js-5.8.min.css');
        $this->page->requires->js('/mod/videofile/video-js-5.21.1/video-js-5.8.min.js', true);

        // A VideoJS plugin for Transcript support.
        $this->page->requires->js('/mod/videofile/video-js-4.12.8/plugins/videojs-transcript/dist/videojs-transcript.js', true);
        $this->page->requires->css('/mod/videofile/video-js-4.12.8/plugins/videojs-transcript/css/videojs-transcript.css');

        // A VideoJS plugin for MPEG-DASH support.
        //$this->page->requires->js('/mod/videofile/video-js-4.12.8/plugins/dash/dash.all.debug.js', true);
        $this->page->requires->js('/mod/videofile/video-js-4.12.8/plugins/dash/dash.all.min.js', true);
        $this->page->requires->js('/mod/videofile/video-js-4.12.8/plugins/dash/videojs-dash.js', true);


        // Set the videojs flash fallback url.
        $swfurl = new moodle_url('/mod/videofile/video-js-4.12.8/video-js.swf');
        $this->page->requires->js_init_code(
            'videojs.options.flash.swf = "' . $swfurl . '";');

        // Yui module handles responsive mode video resizing.
        if ($videofile->get_instance()->responsive || $videofile->get_instance()->transcript) {
            $config = get_config('videofile');

            $this->page->requires->yui_module(
                'moodle-mod_videofile-videojs',
                'M.mod_videofile.videojs.init',
                array($videofile->get_instance()->id,
                      $swfurl,
                      $videofile->get_instance()->width,
                      $videofile->get_instance()->height,
                      (boolean) $config->limitdimensions,
                      (boolean) $videofile->get_instance()->responsive,
                      (boolean) $videofile->get_instance()->transcript));
        }

        // Header setup.
        $this->page->set_title($title);
        $this->page->set_heading($this->page->course->fullname);

        $output .= $this->output->header();
        if ($this->page->pagelayout != "popup" && $this->page->pagelayout != "embedded") {
            $output .= $this->output->heading($name, 3);
            if (!empty($videofile->get_instance()->intro)) {
                $output .= $this->output->box_start('generalbox boxaligncenter', 'intro');
                $output .= format_module_intro('videofile',
                                               $videofile->get_instance(),
                                               $coursemoduleid);
                $output .= $this->output->box_end();
            }

        }

        return $output;
    }

    /**
     * Render the footer
     *
     * @return string
     */
    public function video_footer() {
        return $this->output->footer();
    }

    /**
     * Render the videofile page
     *
     * @param videofile videofile
     * @return string The page output.
     */
    public function video_page($videofile) {
        $output = '';
        $output .= $this->video_header($videofile);
        $output .= $this->video($videofile);
        $output .= $this->video_footer();

        return $output;
    }


    /**
     * Utility function for getting a file URL
     *
     * @param stored_file $file
     * @return string file url
     */
    private function util_get_file_url($file) {
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename(),
            false);
    }

    /**
     * Utility function for getting area files
     *
     * @param int $contextid
     * @param string $areaname file area name (e.g. "videos")
     * @return array of stored_file objects
     */
    private function util_get_area_files($contextid, $areaname) {
        $fs = get_file_storage();
        return $fs->get_area_files($contextid,
                                   'mod_videofile',
                                   $areaname,
                                   false,
                                   'itemid, filepath, filename',
                                   false);
    }

    /**
     * Utility function for getting the video poster image
     *
     * @param int $contextid
     * @return url to the poster image (or the default image)
     */
    private function get_poster_image($contextid) {
        $posterurl = null;
        $posters = $this->util_get_area_files($contextid, 'posters');
        foreach ($posters as $file) {
            $posterurl = $this->util_get_file_url($file);
            break;  // Only one poster allowed.
        }
        if (!$posterurl) {
            $posterurl = $this->pix_url('default-video-thumbnail', 'videofile');
        }

        return $posterurl;
    }

    /**
     * Utility function for creating the video element HTML.
     *
     * @param object $videofile
     * @param url to the video poster image
     * @return string the video element HTML
     */
    private function get_video_element_html($videofile, $posterurl) {
        /* The width and height are set to auto if responsive flag is set
           but is not ignored. They are still used to calculate proportions
           in the javascript that handles video resizing. */
        $width = ($videofile->get_instance()->responsive ?
                  'auto' : $videofile->get_instance()->width);
        $height = ($videofile->get_instance()->responsive ?
                   'auto' : $videofile->get_instance()->height);

        // Renders the video element.
        return html_writer::start_tag(
            'video',
            array('id' => 'videofile-' . $videofile->get_instance()->id,
                  'class' => 'video-js vjs-default-skin',
                  'controls' => 'controls',
                  'preload' => 'auto',
                  'width' => $width,
                  'height' => $height,
                  'poster' => $posterurl,
                  'data-setup' => '{}')
        );
    }

    /**
     * Utility function for creating the video source elements HTML.
     *
     * @param int $contextid
     * @return string HTML
     */
    private function get_video_source_elements_html($contextid) {
        $output = '';
        $videos = $this->util_get_area_files($contextid, 'videos');
        foreach ($videos as $file) {
            if ($mimetype = $file->get_mimetype()) {
                $videourl = $this->util_get_file_url($file);

                $output .= html_writer::empty_tag(
                    'source',
                    array('src' => $videourl,
                          'type' => $mimetype)
                );
            }
        }

        return $output;
    }

    /**
     * Utility function for creating the video source elements HTML.
     *
     * @param int $contextid
     * @return string HTML
     */
    private function get_video_url_source_elements_html($videofile) {
        $output = '';

        $videourl = $videofile->get_instance()->externalurl;
        $ismpegdash = $videofile->get_instance()->mpegdash;
        $mimetype = "video/mp4";

        if ($ismpegdash) {
            $output .= html_writer::empty_tag(
                'source',
                array('src' => $videourl.'/manifest.mpd',
                    'type' => 'application/dash+xml')
            );
        } else {
            $output .= html_writer::empty_tag(
                'source',
                array('src' => $videourl,
                    'type' => $mimetype)
            );
        }

        return $output;
    }

    /**
     * Utility function for creating the video caption track elements
     * HTML.
     *
     * @param int $contextid
     * @return string HTML
     */
    private function get_video_caption_track_elements_html($contextid) {
        $output = '';
        $first = true;
        $captions = $this->util_get_area_files($contextid, 'captions');
        foreach ($captions as $file) {
            if ($mimetype = $file->get_mimetype()) {
                $captionurl = $this->util_get_file_url($file);

                // Get or construct caption label for video.js player.
                $filename = $file->get_filename();
                $dot = strrpos($filename, '.');
                if ($dot) {
                    $label = substr($filename, 0, $dot);
                } else {
                    $label = $filename;
                }

                // Perhaps filename is a three letter ISO 6392 language code (e.g. eng, swe)?
                if (preg_match('/^[a-z]{3}$/', $label)) {
                    $maybelabel = get_string($label, 'core_iso6392');

                    /* Strings not in language files come back as [[string]], don't
                       use those for labels. */
                    if (substr($maybelabel, 0, 2) !== '[[' ||
                            substr($maybelabel, -2, 2) === ']]') {
                        $label = $maybelabel;
                    }
                }

                $options = array('kind' => 'captions',
                                 'src' => $captionurl,
                                 'srclang' => 'en',
                                 'label' => $label);
                if ($first) {
                    $options['default'] = 'default';
                    $first = false;
                }

                // Track seems to need closing tag in IE9 (!).
                $output .= html_writer::tag('track', '', $options);
            }
        }

        return $output;
    }

    /**
     * Utility function for getting the HTML for the alternative video
     * links in case video isn't showing/playing properly.
     *
     * @param int $contextid
     * @return string HTML
     */
    private function get_alternative_video_links_html($contextid) {
        $output = '';
        $videooutput = '';

        $first = true;
        $videos = $this->util_get_area_files($contextid, 'videos');
        foreach ($videos as $file) {
            if ($mimetype = $file->get_mimetype()) {
                $videourl = $this->util_get_file_url($file);

                if ($first) {
                    $first = false;
                } else {
                    $videooutput .= ', ';
                }
                $extension = pathinfo($file->get_filename(), PATHINFO_EXTENSION);
                $videooutput .= html_writer::tag('a',
                                                 $extension,
                                                 array('href' => $videourl));
            }
        }

        return html_writer::tag('div',
                                $output,
                                array('class' => 'videofile-not-playing-msg'));
    }

    /**
     * Renders videofile video.
     *
     * @param videofile $videofile
     * @return string HTML
     */
    public function video(videofile $videofile) {
        $output  = '';
        $contextid = $videofile->get_context()->id;

        // Open videofile div.
        $vclass = ($videofile->get_instance()->responsive ?
                   'videofile videofile-responsive' : 'videofile');
        $output .= $this->output->container_start($vclass);

        // Open video tag.
        $posterurl = $this->get_poster_image($contextid);
        $output .= $this->get_video_element_html($videofile, $posterurl);

        // Elements for video sources.
        if ($videofile->get_instance()->externalurl != null) {
            $output .= $this->get_video_url_source_elements_html($videofile);
        } else {
            $output .= $this->get_video_source_elements_html($contextid);
        }

        // Elements for caption tracks.
        $output .= $this->get_video_caption_track_elements_html($contextid);

        // Close video tag.
        $output .= html_writer::end_tag('video');
        if ($videofile->get_instance()->transcript) {
            $output .= html_writer::div('','videotranscript');
        }

        // Alternative video links in case video isn't showing/playing properly.
//        $output .= $this->get_alternative_video_links_html($contextid);

        // Close videofile div.
        $output .= $this->output->container_end();

        return $output;
    }
}

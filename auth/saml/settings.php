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
 * Admin settings and defaults.
 *
 * @package auth_saml
 * @author Erlend Strømsvik - Ny Media AS
 * @author Piers Harding - made quite a number of changes
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * Authentication Plugin: SAML based SSO Authentication
 *
 * Authentication using SAML2 with SimpleSAMLphp.
 *
 * Based on plugins made by Sergio Gómez (moodle_ssp) and Martin Dougiamas (Shibboleth).
 *
 * 2008-10  Created
 * 2009-07  Added new configuration options
 * 2017-10  Added new Moodle 3.3 configuration settings.php file support
 **/

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    global $CFG, $OUTPUT;

    require_once("courses.php");
    require_once("roles.php");

    // Get saml paramters stored in the saml_config.php
    if(file_exists($CFG->dataroot.'/saml_config.php')) {
        $contentfile = file_get_contents($CFG->dataroot.'/saml_config.php');
        $saml_param = json_decode($contentfile);
    } else if (file_exists('saml_config.php')) {
        $contentfile = file_get_contents('saml_config.php');
        $saml_param = json_decode($contentfile);
    } else {
        $saml_param = new stdClass();
    }

    $config = get_config('auth_saml');
    //var_dump($config);die;
    //var_dump($saml_param);die;

    // Set to defaults if undefined
    if (!isset ($saml_param->samllib)) {
        if (isset ($config->samllib)) {
            $saml_param->samllib = $config->samllib;
        } else {
            $saml_param->samllib = '/var/www/sp/simplesamlphp/lib';
        }
    }
    if (!isset ($saml_param->sp_source)) {
        if(isset ($config->sp_source)) {
            $saml_param->sp_source = $config->sp_source;
        }
        else {
            $saml_param->sp_source = 'saml';
        }
    }
    if (!isset ($saml_param->dosinglelogout)) {
        if(isset ($config->dosinglelogout)) {
            $saml_param->dosinglelogout = $config->dosinglelogout;
        }
        else {
            $saml_param->dosinglelogout = false;
        }
    }
    if (!isset ($config->username)) {
        $config->username = 'eduPersonPrincipalName';
    }
    if (!isset ($config->notshowusername)) {
        $config->notshowusername = 'none';
    }
    if (!isset ($config->supportcourses)) {
        $config->supportcourses = 'nosupport';
    }
    if (!isset ($config->syncusersfrom)) {
        $config->syncusersfrom = '';
    }
    if (!isset ($config->samlcourses)) {
        $config->samlcourses = 'schacUserStatus';
    }
    if (!isset ($config->samllogoimage)) {
        //$config->samllogoimage = 'logo.gif';
        $config->samllogoimage = 'portal-login.jpg';
    }
    if (!isset ($config->samllogoinfo)) {
        $config->samllogoinfo = 'SAML login';
    }
    if (!isset ($config->autologin)) {
        $config->autologin = false;
    }
    if (!isset ($config->samllogfile)) {
        $config->samllogfile = '';
    }
    if (!isset ($config->samlhookfile)) {
        $config->samlhookfile = $CFG->dirroot . '/auth/saml/custom_hook.php';
    }
    if (!isset ($config->moodlecoursefieldid)) {
        $config->moodlecoursefieldid = 'shortname';
    }
    if (!isset ($config->ignoreinactivecourses)) {
        $config->ignoreinactivecourses = true;
    }
    if (!isset ($config->externalcoursemappingdsn)) {
        $config->externalcoursemappingdsn = '';
    }
    if (!isset ($config->externalrolemappingdsn)) {
        $config->externalrolemappingdsn = '';
    }
    if (!isset ($config->externalcoursemappingsql)) {
        $config->externalcoursemappingsql = '';
    }
    if (!isset ($config->externalrolemappingsql)) {
        $config->externalrolemappingsql = '';
    }

    if (!isset ($config->disablejit)) {
        $config->disablejit = false;
    }

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_saml/pluginname', '',
        new lang_string('auth_samldescription', 'auth_saml')));

    $settings->add(new admin_setting_configempty('linktoconfig', 'config', '<a href="../auth/saml/config.php">Old config setting page</a>'));

    // samllib folder.
    $settings->add(new admin_setting_configtext('auth_saml/samllib', get_string('auth_saml_samllib', 'auth_saml'),
        get_string('auth_saml_samllib_description', 'auth_saml') , $saml_param->samllib));

    // sp_source.
    $settings->add(new admin_setting_configtext('auth_saml/sp_source', get_string('auth_saml_sp_source', 'auth_saml'),
        get_string('auth_saml_sp_source_description', 'auth_saml') , $saml_param->sp_source));

    // username.
    $settings->add(new admin_setting_configtext('auth_saml/username', get_string('auth_saml_username', 'auth_saml'),
        get_string('auth_saml_username_description', 'auth_saml') , $saml_param->username));

    // dosinglelogout.
    $settings->add(new admin_setting_configcheckbox('auth_saml/dosinglelogout', get_string('auth_saml_dosinglelogout', 'auth_saml'),
        get_string('auth_saml_dosinglelogout_description', 'auth_saml') , $saml_param->dosinglelogout));

    // samllogoimage.
    $settings->add(new admin_setting_configtext('auth_saml/samllogoimage', get_string('auth_saml_logo_path', 'auth_saml'),
        get_string('auth_saml_logo_path_description', 'auth_saml') , $saml_param->samllogoimage));

    // samllogoinfo.
    $settings->add(new admin_setting_configtextarea('auth_saml/samllogoinfo', get_string('auth_saml_logo_info', 'auth_saml'),
        get_string('auth_saml_logo_info_description', 'auth_saml') , $saml_param->samllogoinfo));

    // autologin.
    $settings->add(new admin_setting_configcheckbox('auth_saml/autologin', get_string('auth_saml_autologin', 'auth_saml'),
        get_string('auth_saml_autologin_description', 'auth_saml') , $saml_param->autologin));

    // samllogfile.
    $settings->add(new admin_setting_configtext('auth_saml/samllogfile', get_string('auth_saml_logfile', 'auth_saml'),
        get_string('auth_saml_logfile_description', 'auth_saml') , $saml_param->samllogfile));

    // samlhookfile.
    $settings->add(new admin_setting_configtext('auth_saml/samlhookfile', get_string('auth_saml_samlhookfile', 'auth_saml'),
        get_string('auth_saml_samlhookfile_description', 'auth_saml') , $saml_param->samlhookfile));

    // disablejit.
    $settings->add(new admin_setting_configcheckbox('auth_saml/disablejit', get_string('auth_saml_disablejit', 'auth_saml'),
        get_string('auth_saml_disablejit_description', 'auth_saml') , $saml_param->disablejit));


    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('saml');
    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
            get_string('auth_fieldlocks_help', 'auth'), false, false);
}

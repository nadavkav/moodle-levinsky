<!--<script src="../saml/resources/moodle_saml.js" type="text/javascript"></script>-->

<link rel="stylesheet" type="text/css" href="../saml/resources/ui.theme.css" />
<link rel="stylesheet" type="text/css" href="../saml/resources/ui.core.css" />
<link rel="stylesheet" type="text/css" href="../saml/resources/ui.tabs.css" />
<link rel="stylesheet" type="text/css" href="../saml/resources/moodle_saml.css" />

<script type="text/javascript" src="../saml/resources/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../saml/resources/jquery-ui-1.7.2.custom.min.js"></script>


<?php
/**
 * @author Erlend Strømsvik - Ny Media AS
 * @author Piers Harding - made quite a number of changes
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package auth/saml
 *
 * Authentication Plugin: SAML based SSO Authentication
 *
 * Authentication using SAML2 with SimpleSAMLphp.
 *
 * Based on plugins made by Sergio Gómez (moodle_ssp) and Martin Dougiamas (Shibboleth).
 *
 * 2008-10  Created
 * 2009-07  Added new configuration options
**/
include_once("../../config.php");

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

    // Set to defaults if undefined
    if (!isset ($saml_param->samllib)) {
        if(isset ($config->samllib)) {
            $saml_param->samllib = $config->samllib;
        }
        else {
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

?>

<div align="right">
    <input type="submit" name="auth_saml_db_reset" value="<?php print_string('auth_saml_db_reset_button', 'auth_saml'); ?>" />
</div>

<table cellspacing="0" cellpadding="5" border="0">

<?php
if (isset($err) && !empty($err)) {

    require_once('error.php');
    auth_saml_error($err, false, $config->samllogfile);

    echo '
    <tr>
        <td class="center" colspan="4" style="background-color: red; color: white;text-">
    ';
    if(isset($err['reset'])) {
        echo $err['reset'];
    }
    else {
        print_string("auth_saml_form_error", "auth_saml");
    }
    echo '
        </td>
    </tr>
    ';
}
?>

<tr valign="top" class="required">
    <td class="right"><?php print_string("auth_saml_samllib", "auth_saml"); ?>:</td>
    <td>
        <input name="samllib" type="text" size="30" value="<?php echo $saml_param->samllib; ?>" />
        <?php
        if (isset($err['samllib'])) {
            echo $OUTPUT->error_text($err['samllib']);
        }
        ?>
    </td>
    <td><?php print_string("auth_saml_samllib_description", "auth_saml"); ?></td>
</tr>

<tr valign="top" class="required">
    <td class="right"><?php print_string("auth_saml_sp_source", "auth_saml"); ?>:</td>
    <td>
        <input name="sp_source" type="text" size="10" value="<?php echo $saml_param->sp_source; ?>" />
        <?php
        if (isset($err['sp_source'])) {
            echo $OUTPUT->error_text($err['sp_source']);
        }
        ?>
    </td>
    <td><?php print_string("auth_saml_sp_source_description", "auth_saml"); ?></td>
</tr>

<tr valign="top" class="required">
    <td class="right"><?php print_string("auth_saml_username", "auth_saml"); ?>:</td>
    <td>
        <input name="username" type="text" size="30" value="<?php echo $config->username; ?>" />
    </td>
    <td><?php print_string("auth_saml_username_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string("auth_saml_dosinglelogout", "auth_saml"); ?>:</td>
    <td>
        <input name="dosinglelogout" type="checkbox" <?php if($saml_param->dosinglelogout) echo 'checked="CHECKED"'; ?> />
    </td>
    <td><?php print_string("auth_saml_dosinglelogout_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string("auth_saml_logo_path", "auth_saml"); ?>:</td>
    <td>
       <input name="samllogoimage" type="text" size="30" value="<?php echo $config->samllogoimage; ?>" />
    </td>
    <td><?php print_string("auth_saml_logo_path_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string("auth_saml_logo_info", "auth_saml"); ?>:</td>
    <td>
       <textarea name="samllogoinfo" type="text" size="30" rows="5" cols="30"><?php echo $config->samllogoinfo; ?></textarea>
    </td>
    <td><?php print_string("auth_saml_logo_info_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string("auth_saml_autologin", "auth_saml"); ?>:</td>
    <td>
        <input name="autologin" type="checkbox" <?php if($config->autologin) echo 'checked="CHECKED"'; ?> />
    </td>
    <td><?php print_string("auth_saml_autologin_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string("auth_saml_logfile", "auth_saml"); ?>:</td>
    <td>
       <input name="samllogfile" type="text" size="30" value="<?php echo $config->samllogfile; ?>" />
    </td>
    <td><?php print_string("auth_saml_logfile_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string("auth_saml_samlhookfile", "auth_saml"); ?>:</td>
    <td>
       <input name="samlhookfile" type="text" size="30" value="<?php echo $config->samlhookfile; ?>" />
       <?php
            if (isset($err['samlhookerror'])) {
                echo $OUTPUT->error_text('<p>' . $err['samlhookerror'] . '</p>');
            }
       ?>

    </td>

    <td><?php print_string("auth_saml_samlhookfile_description", "auth_saml"); ?></td>
</tr>

    <!-- support for saml courses sync removed -->
<tr valign="top">
    <td class="right"><?php print_string("auth_saml_disablejit", "auth_saml"); ?>:</td>
    <td>
        <input name="disablejit" type="checkbox" <?php if($config->disablejit) echo 'checked="CHECKED"'; ?> />
    </td>
    <td><?php print_string("auth_saml_disablejit_description", "auth_saml"); ?></td>
</tr>

<tr valign="top">
    <td class="right"><?php print_string('auth_saml_syncusersfrom', 'auth_saml'); ?>:</td>
    <td>
        <select name="syncusersfrom">
        <option name="none" value="">Disabled</option>
        <?php
            foreach (get_enabled_auth_plugins() as $name) {
                $plugin = get_auth_plugin($name);
                if (method_exists($plugin, 'sync_users')) {
                    print '<option name="' . $name . '" value ="' . $name . '" ' . (($config->syncusersfrom == $name) ? 'selected="selected"' : '') . '>' . $name . '</option>';
                }
            }
        ?>
        </select>
    </td>
    <td><?php print_string("auth_saml_syncusersfrom_description", "auth_saml"); ?></td>
</tr>

<tr valign="top" class="required" id="samlcourses_tr" <?php echo ($config->supportcourses == 'nosupport'? 'style="display:none;"' : '') ?> >
    <td class="right"><?php print_string("auth_saml_courses", "auth_saml"); ?>:</td>
    <td>
       <input name="samlcourses" type="text" size="30" value="<?php echo $config->samlcourses; ?>" />
    </td>
    <td><?php print_string("auth_saml_courses_description", "auth_saml"); ?></td>
</tr>

<tr valign="top" id="moodlecoursefieldid_tr" <?php echo ($config->supportcourses == 'nosupport' ? 'style="display:none;"' : '') ; ?> >
    <td class="right"><?php print_string("auth_saml_course_field_id", "auth_saml"); ?>:</td>
    <td>
       <select name="moodlecoursefieldid">
            <option name="shortname" value="shortname" <?php if($config->moodlecoursefieldid == 'shortname') echo 'selected="selected"'; ?> >Short Name</option>
            <option name="idnumber" value="idnumber" <?php if($config->moodlecoursefieldid == 'idnumber') echo 'selected="selected"'; ?> >Number ID</option>
       </select>
    </td>
    <td><?php print_string("auth_saml_course_field_id_description", "auth_saml"); ?></td>
</tr>

<tr valign="top" id="ignoreinactivecourses_tr" <?php echo ($config->supportcourses == 'nosupport' ? 'style="display:none;"' : '') ; ?> >
    <td class="right"><?php print_string("auth_saml_ignoreinactivecourses", "auth_saml"); ?>:</td>
    <td>
        <input name="ignoreinactivecourses" type="checkbox" <?php if($config->ignoreinactivecourses) echo 'checked="checked"'; ?>/>
    </td>
    <td><?php print_string("auth_saml_ignoreinactivecourses_description", "auth_saml"); ?></td>
</tr>

</table>

<!-- support for saml courses sync removed -->
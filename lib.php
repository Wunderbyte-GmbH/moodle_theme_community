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
 * Community theme.
 *
 * @package    theme_community
 * @copyright  &copy; 2023-onwards G J Barnard.
 * @author     G J Barnard - {@link https://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_community_get_main_scss_content($theme) {
    $parenttheme = theme_config::load('moove');
    return theme_moove_get_main_scss_content($parenttheme);
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string SCSS.
 */
function theme_community_get_extra_scss($theme) {
    $parenttheme = theme_config::load('moove');
    return theme_moove_get_extra_scss($parenttheme);
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string SCSS.
 */
function theme_community_get_pre_scss($theme) {
    $parenttheme = theme_config::load('moove');
    return theme_moove_get_pre_scss($parenttheme);
}

/**
 * Serves the H5P Custom CSS.
 *
 * @param string $filename The filename.
 * @param theme_config $theme The theme config object.
 */
function theme_community_serve_hvp_css($filename, $theme) {
    global $CFG, $PAGE;
    require_once($CFG->dirroot.'/lib/configonlylib.php'); // For min_enable_zlib_compression().

    $PAGE->set_context(context_system::instance());
    $themename = $theme->name;

    $content = theme_community_get_setting('hvpcustomcss');
    $md5content = md5($content);
    $md5stored = get_config('theme_'.$themename, 'hvpccssmd5');
    if ((empty($md5stored)) || ($md5stored != $md5content)) {
        // Content changed, so the last modified time needs to change.
        set_config('hvpccssmd5', $md5content, 'theme_'.$themename);
        $lastmodified = time();
        set_config('hvpccsslm', $lastmodified, 'theme_'.$themename);
    } else {
        $lastmodified = get_config('theme_'.$themename, 'hvpccsslm');
        if (empty($lastmodified)) {
            $lastmodified = time();
        }
    }

    // Sixty days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('HTTP/1.1 200 OK');

    header('Etag: "'.$md5content.'"');
    header('Content-Disposition: inline; filename="'.$filename.'"');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastmodified).' GMT');
    header('Expires: '.gmdate('D, d M Y H:i:s', time() + $lifetime).' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age='.$lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: '.strlen($content));
    }

    echo $content;

    die;
}

/**
 * Get theme setting.
 * @param string $setting
 * @param string $format = false
 */
function theme_community_get_setting($setting, $format = false) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('community');
    }

    if (empty($theme->settings->$setting)) {
        return false;
    } else if (!$format) {
        return $theme->settings->$setting;
    } else if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    } else if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true));
    } else {
        return format_string($theme->settings->$setting);
    }
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_community_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('community');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        // By default, theme files must be cache-able by both browsers and proxies.  From 'More' theme.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        if ($filearea === 'hvp') {
            theme_community_serve_hvp_css($args[1], $theme);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}
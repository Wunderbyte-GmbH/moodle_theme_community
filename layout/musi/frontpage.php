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
 * A drawer based layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2021 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/theme/musi/classes/navbar.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

$theme = theme_config::load('musi');

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new theme_community\output\navigation\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

/* MUSI changes - replace my courses with Musi courses */
$primarymenu['moremenu']['nodearray'] = theme_musi\navbar::customize_navbar($primarymenu['moremenu']['nodearray']);
/* MUSI changes end */

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'footnote' => $theme->settings->footnote,
];


$templatecontext = array_merge($templatecontext, slideshow());

$preset = !empty($this->page->theme->settings->preset) ? $this->page->theme->settings->preset : "null";
$templatecontext["get_compact_logo_url"] = "/theme/musi/scss/preset/$preset/logo.png";

echo $OUTPUT->render_from_template('theme_musi/frontpage', $templatecontext);

function slideshow() {
    global $OUTPUT;

    $theme = theme_config::load('musi');
    $templatecontext['sliderenabled'] = $theme->settings->sliderenabled;

    if (empty($templatecontext['sliderenabled'])) {
        return $templatecontext;
    }

    $slidercount = $theme->settings->slidercount;

    for ($i = 1, $j = 0; $i <= $slidercount; $i++, $j++) {
        $sliderimage = "sliderimage{$i}";
        $slidertitle = "slidertitle{$i}";
        $slidercap = "slidercap{$i}";

        $templatecontext['slides'][$j]['key'] = $j;
        $templatecontext['slides'][$j]['active'] = false;

        $image = $theme->setting_file_url($sliderimage, $sliderimage);
        if (empty($image)) {
            $image = $OUTPUT->image_url('slide_default', 'theme');
        }
        $templatecontext['slides'][$j]['image'] = $image;
        $templatecontext['slides'][$j]['title'] = format_text($theme->settings->$slidertitle);
        $templatecontext['slides'][$j]['caption'] = format_text($theme->settings->$slidercap);

        if ($i === 1) {
            $templatecontext['slides'][$j]['active'] = true;
        }
    }
    return $templatecontext;
}

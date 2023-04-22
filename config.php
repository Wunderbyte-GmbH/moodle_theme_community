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

defined('MOODLE_INTERNAL') || die;

$THEME->doctype = 'html5';
$THEME->name = 'community';
$THEME->parents = array('moove', 'boost');
$THEME->sheets = array();
$THEME->editor_sheets = [];
$THEME->usefallback = false;
$THEME->enable_dock = false;

$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = array();

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->extrascsscallback = 'theme_moove_get_extra_scss';
$THEME->prescsscallback = 'theme_moove_get_pre_scss';
$THEME->precompiledcsscallback = 'theme_moove_get_precompiled_css';
$THEME->scss = function($theme) {
    return theme_moove_get_main_scss_content($theme);
};
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
// By default, all Moodle theme do not need their titles displayed.
$THEME->activityheaderconfig = [
    'notitle' => true
];

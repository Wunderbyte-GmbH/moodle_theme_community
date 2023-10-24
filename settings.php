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
 * @copyright  2023 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('theme_community_privacy', '',
        get_string('privacy:note', 'theme_community')));

    // Parent theme.
    $themes = core_component::get_plugin_list('theme');
    $supportedthemes = array('boost', 'moove', 'musi');
    $name = 'theme_community/parenttheme';
    $title = get_string('parenttheme', 'theme_community');
    $description = get_string('parentthemedesc', 'theme_community');
    $default = 'boost';
    $choices = array();
    foreach ($themes as $theme => $themedir) {
        if (($theme != 'community') && (in_array($theme, $supportedthemes))) {
            $choices[$theme] = ucfirst(get_string('pluginname', 'theme_' . $theme));
        }
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('purge_all_caches'); // Has to be for the config file?
    $settings->add($setting);

    // Site breadcrumbs.
    $name = 'theme_community/sitebreadcrumbs';
    $title = get_string('sitebreadcrumbs', 'theme_community');
    $description = get_string('sitebreadcrumbsdesc', 'theme_community');
    $default = 'on';
    $choices = array(
        'off' => get_string('off', 'theme_community'),
        'on' => get_string('on', 'theme_community'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Course index category select.
    $name = 'theme_community/courseindexcategoryselect';
    $title = get_string('courseindexcategoryselect', 'theme_community');
    $description = get_string('courseindexcategoryselectdesc', 'theme_community');
    $default = 'off';
    $choices = array(
        'off' => get_string('off', 'theme_community'),
        'on' => get_string('on', 'theme_community'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // My courses / Courses.
    $name = 'theme_community/showmycourses';
    $title = get_string('showmycourses', 'theme_community');
    $description = get_string('showmycoursesdesc', 'theme_community');
    $default = 'no';
    $choices = array(
        'no' => get_string('no', 'theme_community'),
        'yes' => get_string('yes', 'theme_community'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Home.
    $name = 'theme_community/showhome';
    $title = get_string('showhome', 'theme_community');
    $description = get_string('showhomedesc', 'theme_community');
    $default = 'no';
    $choices = array(
        'no' => get_string('no', 'theme_community'),
        'yes' => get_string('yes', 'theme_community'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Custom H5P CSS.
    $name = 'theme_community/hvpcustomcss';
    $title = get_string('hvpcustomcss', 'theme_community');
    $description = get_string('hvpcustomcssdesc', 'theme_community');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}

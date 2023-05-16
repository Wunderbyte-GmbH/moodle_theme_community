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

namespace theme_community;

use theme_config;

/**
 * The theme's toolbox.
 *
 * @copyright  &copy; 2023-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class toolbox {

    /**
     * @var toolbox Singleton instance of us.
     */
    protected static $instance = null;

    /**
     * @var the_config The the_config instance for the theme.
     */
    protected $theconfig = null;

    /**
     * @var string The parent theme name.
     */
    protected $parentname = null;

    /**
     * @var the_config The the_config instance for the parent.
     */
    protected $theparentconfig = null;

    /**
     * This is a lonely object.
     */
    private function __construct() {
    }

    /**
     * Gets the toolbox singleton.
     *
     * @return toolbox The toolbox instance.
     */
    public static function get_instance() {
        if (!is_object(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Finds the given setting in the theme using the get_config core function for when the
     * theme_config object has not been created.
     *
     * @param string $setting Setting name.
     * @param themename $themename null(default of 'community' used)|theme name.
     *
     * @return any false|value of setting.
     */
    public function get_config_setting($setting, $themename = null) {
        if (empty($themename)) {
            $themename = 'community';
        }
        return \get_config('theme_'.$themename, $setting);
    }

    /**
     * Get theme setting.
     * @param string $setting
     * @param string $format = false
     */
    public function get_setting($setting, $format = false) {
        if (empty($this->theconfig)) {
            $this->theconfig = theme_config::load('community');
        }

        if (empty($this->theconfig->settings->$setting)) {
            return false;
        } else if (!$format) {
            return $this->theconfig->settings->$setting;
        } else if ($format === 'format_text') {
            return format_text($this->theconfig->settings->$setting, FORMAT_PLAIN);
        } else if ($format === 'format_html') {
            return format_text($this->theconfig->settings->$setting, FORMAT_HTML, array('trusted' => true));
        } else {
            return format_string($this->theconfig->settings->$setting);
        }
    }

    public function getparentthemename(): string {
        $themename = $this->get_config_setting('parenttheme');
        if (empty($themename)) {
            $themename = 'boost';
        }
        return $themename;
    }

    public function getparents(): array {
        $parents = array();
        $themename = $this->getparentthemename();
        if ($themename != 'boost') {
            $parents[] = $themename;
        }
        $parents[] = 'boost';

        return $parents;
    }

    public function getlayouts(): array {
        $themename = $this->getparentthemename();
        $methodname = $themename.'layouts';
        if (method_exists($this, $methodname)) {
            return call_user_func(array($this, $methodname));
        }
        return call_user_func(array($this, 'boostlayouts'));
    }

    private function boostlayouts(): array {
        return array(
            // Most backwards compatible layout without the blocks.
            'base' => array(
                'file' => 'boost/drawers.php',
                'regions' => array(),
            ),
            // Standard layout with blocks.
            'standard' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // Main course page.
            'course' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
                'options' => array('langmenu' => true),
            ),
            'coursecategory' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // Part of course, typical for modules - default page layout if $cm specified in require_login().
            'incourse' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // The site home page.
            'frontpage' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
                'options' => array('nonavbar' => true),
            ),
            // Server administration scripts.
            'admin' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // My courses page.
            'mycourses' => array(
                'file' => 'boost/drawers.php',
                'regions' => ['side-pre'],
                'defaultregion' => 'side-pre',
                'options' => array('nonavbar' => true),
            ),
            // My dashboard page.
            'mydashboard' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
                'options' => array('nonavbar' => true, 'langmenu' => true),
            ),
            // My public page.
            'mypublic' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'login' => array(
                'file' => 'login.php',
                'regions' => array(),
                'options' => array('langmenu' => true),
            ),

            // Pages that appear in pop-up windows - no navigation, no blocks, no header and bare activity header.
            'popup' => array(
                'file' => 'columns1.php',
                'regions' => array(),
                'options' => array(
                    'nofooter' => true,
                    'nonavbar' => true,
                    'activityheader' => [
                        'notitle' => true,
                        'nocompletion' => true,
                        'nodescription' => true
                    ]
                )
            ),
            // No blocks and minimal footer - used for legacy frame layouts only!
            'frametop' => array(
                'file' => 'columns1.php',
                'regions' => array(),
                'options' => array(
                    'nofooter' => true,
                    'nocoursefooter' => true,
                    'activityheader' => [
                        'nocompletion' => true
                    ]
                ),
            ),
            // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
            'embedded' => array(
                'file' => 'embedded.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
            // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
            // Please be extremely careful if you are modifying this layout.
            'maintenance' => array(
                'file' => 'maintenance.php',
                'regions' => array(),
            ),
            // Should display the content and basic headers only.
            'print' => array(
                'file' => 'columns1.php',
                'regions' => array(),
                'options' => array('nofooter' => true, 'nonavbar' => false, 'noactivityheader' => true),
            ),
            // The pagelayout used when a redirection is occuring.
            'redirect' => array(
                'file' => 'embedded.php',
                'regions' => array(),
            ),
            // The pagelayout used for reports.
            'report' => array(
                'file' => 'boost/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // The pagelayout used for safebrowser and securewindow.
            'secure' => array(
                'file' => 'secure.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre'
            )
        );
    }

    private function moovelayouts(): array {
        return array(
            // Most backwards compatible layout without the blocks.
            'base' => array(
                'file' => 'moove/drawers.php',
                'regions' => array(),
            ),
            // Standard layout with blocks.
            'standard' => array(
                'file' => 'moove/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // Main course page.
            'course' => array(
                'file' => 'moove/course.php',
                'regions' => array('side-pre', 'content'),
                'defaultregion' => 'side-pre',
                'options' => array('langmenu' => true),
            ),
            'coursecategory' => array(
                'file' => 'moove/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // Part of course, typical for modules - default page layout if $cm specified in require_login().
            'incourse' => array(
                'file' => 'moove/incourse.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // The site home page.
            'frontpage' => array(
                'file' => 'moove/frontpage.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
                'options' => array('nonavbar' => true),
            ),
            // Server administration scripts.
            'admin' => array(
                'file' => 'moove/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // My courses page.
            'mycourses' => array(
                'file' => 'moove/drawers.php',
                'regions' => ['side-pre'],
                'defaultregion' => 'side-pre',
                'options' => array('nonavbar' => true),
            ),
            // My dashboard page.
            'mydashboard' => array(
                'file' => 'moove/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
                'options' => array('nonavbar' => true, 'langmenu' => true),
            ),
            // My public page.
            'mypublic' => array(
                'file' => 'moove/mypublic.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'login' => array(
                'file' => 'login.php',
                'regions' => array(),
                'options' => array('langmenu' => true),
            ),
            // Pages that appear in pop-up windows - no navigation, no blocks, no header and bare activity header.
            'popup' => array(
                'file' => 'columns1.php',
                'regions' => array(),
                'options' => array(
                    'nofooter' => true,
                    'nonavbar' => true,
                    'activityheader' => [
                        'notitle' => true,
                        'nocompletion' => true,
                        'nodescription' => true
                    ]
                )
            ),
            // No blocks and minimal footer - used for legacy frame layouts only!
            'frametop' => array(
                'file' => 'columns1.php',
                'regions' => array(),
                'options' => array(
                    'nofooter' => true,
                    'nocoursefooter' => true,
                    'activityheader' => [
                        'nocompletion' => true
                    ]
                ),
            ),
            // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
            'embedded' => array(
                'file' => 'embedded.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
            // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
            // Please be extremely careful if you are modifying this layout.
            'maintenance' => array(
                'file' => 'maintenance.php',
                'regions' => array(),
            ),
            // Should display the content and basic headers only.
            'print' => array(
                'file' => 'columns1.php',
                'regions' => array(),
                'options' => array('nofooter' => true, 'nonavbar' => false, 'noactivityheader' => true),
            ),
            // The pagelayout used when a redirection is occuring.
            'redirect' => array(
                'file' => 'embedded.php',
                'regions' => array(),
            ),
            // The pagelayout used for reports.
            'report' => array(
                'file' => 'moove/drawers.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            // The pagelayout used for safebrowser and securewindow.
            'secure' => array(
                'file' => 'secure.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre'
            )
        );
    }

    private function musilayouts(): array {
        return array(
            'login' => array(
                'file' => 'login.php',
                'regions' => array(),
                'options' => array('langmenu' => true),
            ),
            // The site home page.
            'base' => array(
                'file' => 'musi/columns2.php',
                'regions' => array(),
                'defaultregion' => 'side-pre',
            ),
            'frontpage' => array(
                'file' => 'musi/frontpage.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'standard' => array(
                'file' => 'musi/columns2.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'course' => array(
                'file' => 'musi/columns2.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'mydashboard' => array(
                'file' => 'musi/user_dashboard.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'incourse' => array(
                'file' => 'musi/columns2.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'coursecategory' => array(
                'file' => 'musi/columns2.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            ),
            'admin' => array(
                'file' => 'musi/columns2.php',
                'regions' => array('side-pre'),
                'defaultregion' => 'side-pre',
            )
        );
    }

    public function getprecompiledcsscallback(): string {
        $themename = $this->getparentthemename();
        switch($themename) {
            case 'moove':
                $callback = 'theme_moove_get_precompiled_css';
                break;
            default:
                $callback = 'theme_boost_get_precompiled_css';
        }
        return $callback;
    }

    private function getparentconfig(): theme_config {
        if (empty($this->theparentconfig)) {
            $parentthemename = $this->getparentthemename();
            $this->theparentconfig = theme_config::load($parentthemename);
        }
        return $this->theparentconfig;
    }

    public function getparentmainscsscontent(): string {
        $parentthemename = $this->getparentthemename();
        $parenttheme = $this->getparentconfig();
        switch($parentthemename) {
            case 'moove':
                $scss = theme_moove_get_main_scss_content($parenttheme);
                break;
            case 'musi':
                $scss = theme_musi_get_main_scss_content($parenttheme);
                break;
            default:
                $scss = theme_boost_get_main_scss_content($parenttheme);
        }
        return $scss;
    }

    public function getparentextrascss(): string {
        $parentthemename = $this->getparentthemename();
        $parenttheme = $this->getparentconfig();
        switch($parentthemename) {
            case 'moove':
                $scss = theme_moove_get_extra_scss($parenttheme);
                break;
            default:
                $scss = theme_boost_get_extra_scss($parenttheme);
        }
        return $scss;
    }

    public function getparentprescss(): string {
        $parentthemename = $this->getparentthemename();
        $parenttheme = $this->getparentconfig();
        switch($parentthemename) {
            case 'moove':
                $scss = theme_moove_get_pre_scss($parenttheme);
                break;
            default:
                $scss = theme_boost_get_pre_scss($parenttheme);
        }
        return $scss;
    }
}

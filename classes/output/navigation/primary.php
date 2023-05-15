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

namespace theme_community\output\navigation;

use custom_menu;
use renderer_base;

/**
 * Community theme.
 * Primary navigation renderable
 *
 * @package    theme_community
 * @category   navigation
 * @copyright  &copy; 2023-onwards G J Barnard.  Based upon work done by Peter Dias.
 * @author     G J Barnard - {@link https://moodle.org/user/profile.php?id=442195}
 * @copyright  2021 onwards Peter Dias
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class primary extends \core\navigation\output\primary {
    /** @var moodle_page $page the moodle page that the navigation belongs to */
    private $page = null;

    /**
     * primary constructor.
     * @param \moodle_page $page
     */
    public function __construct($page) {
        $this->page = $page;
        parent::__construct($page);
    }

    /**
     * Combine the various menus into a standardized output.
     *
     * @param renderer_base|null $output
     * @return array
     */
    public function export_for_template(?renderer_base $output = null): array {
        if (!$output) {
            $output = $this->page->get_renderer('core');
        }

        $menudata = array_merge($this->get_primary_nav(), $this->get_custom_menu($output));
        $keystoremove = array();
        if ($output->get_setting_value('showmycourses') == 'no') {
            $keystoremove['mycourses'] = 'mycourses';
        }
        if ($output->get_setting_value('showhome') == 'no') {
            $keystoremove['home'] = 'home';
        }
        if (!empty($keystoremove)) {
            $replacementmenudata = array();
            foreach ($menudata as $menuentry) {
                $menuentry = (object) $menuentry;
                if (!empty($menuentry->key)) {
                    if (in_array($menuentry->key, $keystoremove)) {
                        unset($keystoremove[$menuentry->key]);
                    } else {
                        $replacementmenudata[] = $menuentry;
                    }
                }
            }
            $menudata = $replacementmenudata;
        }
        $menudata = (object) $menudata;
        $moremenu = new \core\navigation\output\more_menu($menudata, 'navbar-nav', false);
        $mobileprimarynav = array_merge($this->get_primary_nav(), $this->get_custom_menu($output));

        $languagemenu = new \core\output\language_menu($this->page);

        return [
            'mobileprimarynav' => $mobileprimarynav,
            'moremenu' => $moremenu->export_for_template($output),
            'lang' => !isloggedin() || isguestuser() ? $languagemenu->export_for_template($output) : [],
            'user' => $this->get_user_menu($output),
        ];
    }
}

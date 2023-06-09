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

namespace theme_community\output;

trait core_renderer_trait {
    /**
     * This renders the navbar.
     */
    public function navbar(): string {
        $retr = '';
        $sitebreadcrumbs = $this->get_setting_value('sitebreadcrumbs');
        if (!empty($sitebreadcrumbs)) {
            if ($sitebreadcrumbs == 'on') {
                $newnav = new \theme_community\communitynavbar($this->page, $this);
                $retr = $this->render_from_template('core/navbar', $newnav);
            }
        } else {
            $newnav = new \theme_community\communitynavbar($this->page, $this);
            $retr = $this->render_from_template('core/navbar', $newnav);
        }
        return $retr;
    }

    /**
     * Gets the theme setting value for the given theme setting name.
     *
     * @param string $name The name of the setting.
     *
     * return Setting value or null.
     */
    public function get_setting_value($name) {
        $retr = null;
        if (!empty($this->page->theme->settings->$name)) {
            $retr = $this->page->theme->settings->$name;
        }
        return $retr;
    }
}

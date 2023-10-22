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

namespace theme_community;

/**
 * Creates a navbar for Community that allows easy control of the navbar items.
 *
 * @package    theme_community
 * @copyright  2021 Adrian Greeve <adrian@moodle.com>
 * @copyright  &copy; 2023-onwards G J Barnard.
 * @author     G J Barnard - {@link https://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class communitynavbar extends \theme_boost\boostnavbar {
    /** @var core_renderer $output the core renderer that the navigation belongs to */
    private $output = null;

    /**
     * Takes a navbar object and picks the necessary parts for display.
     *
     * @param \moodle_page $page The current moodle page.
     */
    public function __construct(\moodle_page $page, $output) {

        $this->output = $output;
        parent::__construct($page);
    }

    /**
     * Prepares the navigation nodes for use with boost.
     */
    protected function prepare_nodes_for_boost(): void {
        if ($this->output->get_setting_value('showmycourses') == 'no') {
            $this->remove('mycourses');
            $this->remove('courses');
        }
        if ($this->output->get_setting_value('showhome') == 'no') {
            $this->remove('home');
            $this->remove('myhome');
        }
    }
}

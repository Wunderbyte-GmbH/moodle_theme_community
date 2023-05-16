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

$toolbox = \theme_community\toolbox::get_instance();
$parentthemename = $toolbox->getparentthemename();

switch($parentthemename) {
    case 'moove':
        class core_renderer extends \theme_moove\output\core_renderer {
            use core_renderer_trait;
        }
        break;
    default:
        class core_renderer extends \theme_boost\output\core_renderer {
            use core_renderer_trait;
        }
}

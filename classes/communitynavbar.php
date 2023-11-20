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

use breadcrumb_navigation_node;
use core_course_category;
use context_course;
use context_coursecat;
use moodle_url;
use navigation_node;

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
        $this->page = $page;

        if (
            (empty($this->page->navbar->get_items())) &&
            (!empty($this->page->cm->id)) &&
            ($this->page->course->format == "singleactivity")
        ) {
            $node = $this->find_key_node($this->page->navigation, $this->page->cm->id);
            if ($node !== false) {
                $this->build_items_from_node($node);
                if ($this->output->get_setting_value('showhome') == 'yes') {
                    $this->add_home();
                }
                $last = reset($this->items);
                if ($last) {
                    $last->set_last(true);
                }
                $this->items = array_reverse($this->items);
            }
            $this->prepare_nodes_for_boost();
        } else {
            parent::__construct($page);
        }
    }

    protected function find_key_node($node, $key) {
        if ($node->key == $key) {
            return $node;
        } else {
            foreach ($node->children as &$child) {
                $outcome = $this->find_key_node($child, $key);
                if ($outcome !== false) {
                    return $outcome;
                }
            }
        }
        return false;
    }

    protected function build_items_from_node($navigationnode) {
        global $CFG;
        while ($navigationnode && $navigationnode->parent !== null) {
            if (!$navigationnode->mainnavonly) {
                $this->items[] = new breadcrumb_navigation_node($navigationnode);
            }
            if (!empty($CFG->navshowcategories) &&
                $navigationnode->type === navigation_node::TYPE_COURSE &&
                $navigationnode->parent->key === 'currentcourse') {
                foreach ($this->get_course_categories() as $item) {
                    $this->items[] = new breadcrumb_navigation_node($item);
                }
            }
            $navigationnode = $navigationnode->parent;
        }
    }

    /**
     * Get the list of categories leading to this course.
     *
     * Adapted from reference version in navigationlib.php
     *
     * This function is used by {@link navbar::get_items()} to add back the "courses"
     * node and category chain leading to the current course.  Note that this is only ever
     * called for the current course, so we don't need to bother taking in any parameters.
     *
     * @return array
     */
    private function get_course_categories() {
        global $CFG;
        require_once($CFG->dirroot.'/course/lib.php');

        $categories = array();
        $cap = 'moodle/category:viewhiddencategories';
        $showcategories = !core_course_category::is_simple_site();

        if ($showcategories) {
            foreach ($this->page->categories as $category) {
                $context = context_coursecat::instance($category->id);
                if (!core_course_category::can_view_category($category)) {
                    continue;
                }

                $displaycontext = \context_helper::get_navigation_filter_context($context);
                $url = new moodle_url('/course/index.php', ['categoryid' => $category->id]);
                $name = format_string($category->name, true, ['context' => $displaycontext]);
                $categorynode = breadcrumb_navigation_node::create($name, $url, navigation_node::TYPE_CATEGORY, null, $category->id);
                if (!$category->visible) {
                    $categorynode->hidden = true;
                }
                $categories[] = $categorynode;
            }
        }

        // Don't show the 'course' node if enrolled in this course.
        $coursecontext = context_course::instance($this->page->course->id);
        if (!is_enrolled($coursecontext, null, '', true)) {
            $courses = $this->page->navigation->get('courses');
            if (!$courses) {
                // Courses node may not be present.
                $courses = breadcrumb_navigation_node::create(
                    get_string('courses'),
                    new moodle_url('/course/index.php'),
                    navigation_node::TYPE_CONTAINER
                );
            }
            $categories[] = $courses;
        }

        return $categories;
    }

    private function add_home() {
        global $CFG;
        $defaulthomepage = get_home_page();
        if ($defaulthomepage == HOMEPAGE_SITE) {
            $this->items[] = breadcrumb_navigation_node::create(
                get_string('home'),
                new moodle_url('/'),
                navigation_node::TYPE_SETTING
            );
        } else {
            if (isloggedin() && !isguestuser()) {  // Makes no sense if you aren't logged in
                if (!empty($CFG->enabledashboard)) {
                    $this->items[] = breadcrumb_navigation_node::create(
                        get_string('myhome'),
                        new moodle_url('/my/'),
                        navigation_node::TYPE_SETTING
                    );
                }
            }
        }
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

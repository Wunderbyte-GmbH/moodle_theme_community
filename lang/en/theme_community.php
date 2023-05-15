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

$string['choosereadme'] = '<div class="clearfix"><h2>Community</h2>'.
'<h3>About</h3>'.
'<p>Community is a basic child theme of the Moove theme.</p>'.
'<h3>Theme Credits</h3>'.
'<p>Author: G J Barnard<br>'.
'Contact: <a href="https://moodle.org/user/profile.php?id=442195">Moodle profile</a><br>'.
'Website: <a href="https://gjbarnard.co.uk">gjbarnard.co.uk</a>'.
'</p>'.
'<h3>More information</h3>'.
'<p><a href="community/Readme.md">How to use this theme.</a></p>'.
'</div></div>';

$string['configtitle'] = 'Community';
$string['pluginname'] = 'Community';

$string['region-side-pre'] = 'Right';

$string['off'] = 'Off';
$string['on'] = 'On';

$string['no'] = 'No';
$string['yes'] = 'Yes';

// Settings.
// Navbar / Breadcrumb.
$string['sitebreadcrumbs'] = 'Site breadcrumbs';
$string['sitebreadcrumbsdesc'] = 'Turn the breadcrumbs on or off at the site level.';

// Course index category select.
$string['courseindexcategoryselect'] = 'Course index category select';
$string['courseindexcategoryselectdesc'] = 'Show the category select on the course index page.';

// My / courses.
$string['showmycourses'] = 'My courses / Courses';
$string['showmycoursesdesc'] = 'Show \'My courses\' / \'Courses\' on the navbar and breadcrumb.';

// Home.
$string['showhome'] = 'Home';
$string['showhomedesc'] = 'Show \'Home\' on the navbar and \'Dashboard\' on the breadcrumb.';

// H5P Custom CSS.
$string['hvpcustomcss'] = 'H5P Custom CSS';
$string['hvpcustomcssdesc'] = 'Custom CSS for the H5P module.';

// Privacy.
$string['privacy:nop'] = 'The Community theme stores has settings that pertain to its configuration.  It also may inherit settings and user preferences from the parent Boost theme, please examine the \'Plugin privacy compliance registry\' for \'Boost\' for details.  For the settings, it is your responsibility to ensure that no user data is entered in any of the free text fields.  Setting a setting will result in that action being logged within the core Moodle logging system against the user whom changed it, this is outside of the themes control, please see the core logging system for privacy compliance for this.  When uploading images, you should avoid uploading images with embedded location data (EXIF GPS) included or other such personal data.  It would be possible to extract any location / personal data from the images.  Please examine the code carefully to be sure that it complies with your interpretation of your privacy laws.  I am not a lawyer and my analysis is based on my interpretation.  If you have any doubt then remove the theme forthwith.';

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
 * Teachers block.
 *
 * @package    block_teachers
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Teachers block class.
 */
class block_teachers extends block_base {

    /**
     * Initialize the block.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_teachers');
    }

    /**
     * Set the block title based on instance configuration.
     */
    public function specialization() {
        if (isset($this->config->title) && trim($this->config->title) !== '') {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }
    }

    /**
     * Multiple instances allowed.
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Which page types this block may appear on.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
            'my' => true,
            'site-index' => true,
            'course-view' => true,
        ];
    }

    /**
     * Instance configuration is allowed.
     *
     * @return bool
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * No site-wide config needed.
     *
     * @return bool
     */
    public function has_config() {
        return false;
    }

    /**
     * Return the block content.
     *
     * @return stdClass
     */
    public function get_content() {
        global $USER, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        // Check if user is logged in.
        if (!isloggedin() || isguestuser()) {
            return $this->content;
        }

        // Get the renderer.
        $renderer = $this->page->get_renderer('block_teachers');
        
        // Render the teachers list.
        $this->content->text = $renderer->render_teachers_list($USER->id);

        return $this->content;
    }
}

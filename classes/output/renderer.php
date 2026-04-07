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
 * Renderer for Teachers block.
 *
 * @package    block_teachers
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_teachers\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;

/**
 * Renderer class for Teachers block.
 */
class renderer extends plugin_renderer_base {

    /**
     * Render the teachers list for a student.
     *
     * @param int $studentid Student user ID
     * @return string HTML content
     */
    public function render_teachers_list($studentid) {
        $teachers = $this->get_student_teachers($studentid);
        
        $data = [
            'teachers' => $teachers['items'],
            'hasteachers' => $teachers['hasitems'],
        ];

        return $this->render_from_template('block_teachers/teacherslist', $data);
    }

    /**
     * Get all teachers for a student's enrolled courses.
     *
     * @param int $studentid Student user ID
     * @return array Teachers data
     */
    private function get_student_teachers($studentid) {
        global $DB, $OUTPUT;

        // Get all courses the student is enrolled in.
        $courses = enrol_get_users_courses($studentid, true);
        
        if (empty($courses)) {
            return ['items' => [], 'hasitems' => false];
        }

        $teachersdata = [];
        $uniqueteachers = [];

        foreach ($courses as $course) {
            $coursecontext = \context_course::instance($course->id);
            
            // Get teacher and editing teacher roles.
            $teacherroles = $DB->get_records_sql(
                "SELECT id FROM {role} WHERE archetype IN ('manager', 'editingteacher', 'teacher')"
            );

            if (empty($teacherroles)) {
                continue;
            }

            $roleids = array_keys($teacherroles);
            list($insql, $params) = $DB->get_in_or_equal($roleids);
            $params[] = $coursecontext->id;

            // Get teachers for this course.
            $teachers = $DB->get_records_sql(
                "SELECT DISTINCT u.id, u.firstname, u.lastname, u.email, u.phone2, u.picture, u.imagealt,
                        u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename
                 FROM {user} u
                 JOIN {role_assignments} ra ON ra.userid = u.id
                 WHERE ra.roleid $insql AND ra.contextid = ?
                 ORDER BY u.lastname, u.firstname",
                $params
            );

            foreach ($teachers as $teacher) {
                if (!isset($uniqueteachers[$teacher->id])) {
                    $uniqueteachers[$teacher->id] = [
                        'id' => $teacher->id,
                        'fullname' => fullname($teacher),
                        'email' => $teacher->email,
                        'phone' => !empty($teacher->phone2) ? $teacher->phone2 : '',
                        'hasphone' => !empty($teacher->phone2),
                        'picture' => $OUTPUT->user_picture($teacher, ['size' => 50, 'link' => false, 'class' => 'rounded-circle']),
                        'profileurl' => new \moodle_url('/user/profile.php', ['id' => $teacher->id]),
                        'messageurl' => new \moodle_url('/message/index.php', ['id' => $teacher->id]),
                        'courses' => [],
                    ];
                }
                $uniqueteachers[$teacher->id]['courses'][] = format_string($course->fullname);
            }
        }

        // Convert to array and add courses list.
        foreach ($uniqueteachers as $teacher) {
            $teacher['courseslist'] = implode(', ', $teacher['courses']);
            $teacher['coursescount'] = count($teacher['courses']);
            $teachersdata[] = $teacher;
        }

        return [
            'items' => $teachersdata,
            'hasitems' => !empty($teachersdata),
        ];
    }
}

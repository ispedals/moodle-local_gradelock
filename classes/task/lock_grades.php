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
 * The grade locking task
 *
 * Change $TIMELIMITHOURS to change the cutoff time for when a grade should be locked.
 *
 * @package    local_gradelock
 * @copyright  2015 Abir Viqar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gradelock\task;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->libdir . '/grade/grade_item.php');
require_once($CFG->libdir . '/grade/grade_grade.php');

class lock_grades extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('taskname', 'local_gradelock');
    }

    public function execute() {
        global $DB;
        $TIMELIMITHOURS = 8;
        $timebound = time() - ($TIMELIMITHOURS * 60 * 60);
        $sql = 'SELECT id, itemid, userid FROM {grade_grades} WHERE locked = 0 AND overridden > 0 AND overridden < ?';
        $records = $DB->get_records_sql($sql, array($timebound));
        foreach($records as $record){
            $gradeitem = \grade_item::fetch(array('id' => $record->itemid));
            $grades = \grade_grade::fetch_users_grades($gradeitem, array($record->userid));
            $grades[$record->userid]->set_locked(1);
        }
    }
}
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
 * Test cases
 *
 * @package    local_gradelock
 * @copyright  2015 Abir Viqar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/lib/grade/tests/fixtures/lib.php');
require_once($CFG->dirroot . '/local/gradelock/classes/task/lock_grades.php');

class local_gradelock_task_testcase extends grade_base_testcase {

    public function test_task() {
        $task = new \local_gradelock\task\lock_grades();
        $grade_grade = new grade_grade();
        $grade_grade->itemid = $this->grade_items[0]->id;
        $grade_grade->userid = 10;
        $grade_grade->rawgrade = 88;
        $grade_grade->rawgrademax = 110;
        $grade_grade->rawgrademin = 18;
        $grade_grade->load_grade_item();
        $grade_grade->insert();

        $grade_grade->grade_item->update_final_grade($this->user[0]->id, 100, 'gradebook', '', FORMAT_MOODLE);
        $grade_grade = grade_grade::fetch(array('userid' => $this->user[0]->id, 'itemid' => $this->grade_items[0]->id));

        $grade_grade->overridden = 1;
        $grade_grade->update();
        $task->execute();
        $grade_grade = grade_grade::fetch(array('userid' => $this->user[0]->id, 'itemid' => $this->grade_items[0]->id));
        $this->assertTrue($grade_grade->is_locked());
    }

    public function test_task_timefilter() {
        $task = new \local_gradelock\task\lock_grades();
        $grade_grade = new grade_grade();
        $grade_grade->itemid = $this->grade_items[0]->id;
        $grade_grade->userid = 10;
        $grade_grade->rawgrade = 88;
        $grade_grade->rawgrademax = 110;
        $grade_grade->rawgrademin = 18;
        $grade_grade->load_grade_item();
        $grade_grade->insert();

        $grade_grade->grade_item->update_final_grade($this->user[0]->id, 100, 'gradebook', '', FORMAT_MOODLE);
        $grade_grade->update();
        $task->execute();
        $grade_grade = grade_grade::fetch(array('userid' => $this->user[0]->id, 'itemid' => $this->grade_items[0]->id));
        $this->assertFalse($grade_grade->is_locked());
    }
}

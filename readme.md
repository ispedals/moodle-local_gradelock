#local_gradelock

This plugin schedules a task that runs every hour to lock grades after a certain period if they have been edited.
This is so that people with `moodle/grade:edit` but not `moodle/grade:lock` cannot change the grade after that period.

Change `$TIMELIMITHOURS` in `classes/task/lock_grades.php` to change the cut-off time. It defaults to 8 hours.

Only tested on Moodle 2.8.

[Download here](https://github.com/ispedals/moodle-local_gradelock/releases/latest).
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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_connect_activity_task
 */

/**
 * Structure step to restore one connect activity
 */
class restore_rtvideo_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('rtvideo', '/activity/rtvideo');
        $paths[] = new restore_path_element('rtvideo_grade', '/activity/rtvideo/grades/grade');
        if ($userinfo) {
            $paths[] = new restore_path_element('rtvideo_entry', '/activity/rtvideo/entries/entry');
        }

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_rtvideo($data) {
        global $DB, $CFG;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the connect record
        $newitemid = $DB->insert_record('rtvideo', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function process_rtvideo_grade($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->rtvideoid = $this->get_new_parentid('rtvideo');
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record('rtvideo_grading', $data);
        $this->set_mapping('rtvideo_grading', $oldid, $newitemid);
    }

    protected function process_rtvideo_entry($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->rtvideoid = $this->get_new_parentid('rtvideo');
        //$data->gradeid = $this->get_mappingid('rtvideo_grading', $oldid);
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record('rtvideo_entries', $data);
        // No need to save this mapping as far as nothing depend on it
        // (child paths, file areas nor links decoder)
    }

    protected function after_execute() {
        // Add connect related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_rtvideo', 'intro', null);
        // Add force icon related files, matching by item id (connect)
        $this->add_related_files('mod_rtvideo', 'content', null);
    }
}

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
 * @package connect
 * @subpackage backup-moodle2
 * @copyright 2012 Gary Menezes {@link http://www.refineddata.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_connect_activity_task
 */

/**
 * Define the complete connect structure for backup, with file and id annotations
 */
class backup_rtvideo_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $rtvideo = new backup_nested_element('rtvideo', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'videourl', 'ccurl', 'displayoncourse', 'width', 'height', 
            'layoverurl', 'detailgrading', 'timecreated',  'timemodified', 'aspectratio',
            'displaycontrols', 'autostart', 'muteoption', 'renderingmode', 'androidhls' ));

        $entries = new backup_nested_element('entries');

        $entry   = new backup_nested_element('entry', array('id'), array(
            'rtvideoid', 'userid', 'positions', 'views', 'grade',
            'timemodified'));

        $grades = new backup_nested_element('grades');

        $grade  = new backup_nested_element('grade', array('id'), array(
            'threshold', 'grade', 'timemodified'));

        // Build the tree
        $rtvideo->add_child($entries);
        $entries->add_child($entry);

        $rtvideo->add_child($grades);
        $grades->add_child($grade);

        // Define sources
        $rtvideo->set_source_table('rtvideo', array('id' => backup::VAR_ACTIVITYID));

        $grade->set_source_sql('
            SELECT *
              FROM {rtvideo_grading}
             WHERE rtvideoid = ?',
            array(backup::VAR_PARENTID));

        // All the rest of elements only happen if we are including user info
        if ($userinfo) {
            $entry->set_source_table('rtvideo_entries', array('rtvideoid' => '../../id'));
        }

        // Define id annotations
        $entry->annotate_ids('user', 'userid');

        // Define file annotations
        $rtvideo->annotate_files('mod_rtvideo', 'intro', null); // This file area hasn't itemid
        $rtvideo->annotate_files('mod_rtvideo', 'content', null); // By rtvideo->id

        // Return the root element (connect), wrapped into standard activity structure
        return $this->prepare_activity_structure($rtvideo);
    }
}

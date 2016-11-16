<?php 

/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */
require_once($CFG->dirroot.'/mod/rtvideo/db/lib.php');

function xmldb_rtvideo_upgrade($oldversion = 0) {
    global $CFG, $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2015082700) {
        $table = new xmldb_table('rtvideo');
        $field = new xmldb_field('aspectratio', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('displaycontrols', XMLDB_TYPE_INTEGER,1 , null, XMLDB_NOTNULL, null, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('autostart', XMLDB_TYPE_INTEGER,1 , null, XMLDB_NOTNULL, null, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('muteoption', XMLDB_TYPE_INTEGER,1 , null, XMLDB_NOTNULL, null, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('renderingmode', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('androidhls', XMLDB_TYPE_INTEGER,1 , null, XMLDB_NOTNULL, null, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }        
    }
    
    if ($oldversion < 2016011200) {
        mod_rtvideo_migrate_connect_video();
    }
    
    return true;
}

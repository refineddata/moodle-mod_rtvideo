<?php
/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/mod/rtvideo/db/lib.php');

function xmldb_rtvideo_install() {
    global $DB, $CFG;
   
    $dbman = $DB->get_manager();
        
    mod_rtvideo_migrate_connect_video();
}

